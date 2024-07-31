<?php

namespace App\EasyAdmin\EventSubscriber\CustomFields;

use App\EasyAdmin\Events\EasyAdmin\CustomBeforeEntityPersistedEvent;
use App\EasyAdmin\Events\EasyAdmin\CustomBeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class CustomPropertyFieldSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PropertyAccessorInterface $propertyAccessor
    ) { }

    public static function getSubscribedEvents()
    {
        return [
            CustomBeforeEntityPersistedEvent::class => ['catchCustomParamsField'],
            CustomBeforeEntityUpdatedEvent::class => ['catchCustomParamsField']
        ];
    }

    /**
     * Processes the data obtained from a custom parameter field
     */
    public function catchCustomParamsField(CustomBeforeEntityPersistedEvent|CustomBeforeEntityUpdatedEvent $event)
    {
        $request = $event->getContext()->getRequest()->request->all();
        $entityName = $event->getContext()->getEntity()->getName();
        $entityInstance = $event->getEntityInstance();

        // Go through all the form parameters and find custom ones to process before saving the entity
        foreach ($request[$entityName] as $propertyPath => $property) {
            if (is_array($property) && isset($property['isCustomParams']) && $property['isCustomParams'] == true) {
                unset($property['isCustomParams']);

                // Check if this field can be read/written to the entity
                if (!$this->propertyAccessor->isReadable($entityInstance, $propertyPath)
                    || !$this->propertyAccessor->isWritable($entityInstance, $propertyPath)
                ) {
                    continue;
                }

                // Get the original json before the change and the original json from the field in the form
                $entityPropertyValue = $this->propertyAccessor->getValue($entityInstance, $propertyPath);
                $propertyJson = $property['json'];

                // If the original json was changed in the form, save it to an entity, skipping further steps in processing the form.
                if ($entityPropertyValue != $propertyJson) {
                    $this->propertyAccessor->setValue($entityInstance, $propertyPath, $propertyJson);
                    continue;
                }

                /**
                 * If the changes took place specifically in the form, but not in the field with the original json - delete it as more unnecessary,
                 * and normalize every remaining key in the final json, which will go to record in the entity
                 */
                unset($property['json']);
                foreach ($property as &$jsonString) {
                    $jsonString = $this->normalizeJsonString($jsonString);
                }

                // Delete keys with empty values and save the resulting json string
                $property = array_filter($property, fn($value) => $value !== '');
                $jsonData = json_encode($property);
                $this->propertyAccessor->setValue($entityInstance, $propertyPath, $jsonData);
            }
        }
    }

    /**
     * Normalizes the json after pretty print, and also removes unnecessary artifacts left by easyadmin or humans.
     * Normalization means removing extra spaces and line breaks
     * For example: '{"key1":  "va lue", \n\r     "key2":   "value2"}' => '{"key1":"va lue","key2":"value2"}'
     */
    private function normalizeJsonString(string $jsonString): string
    {
        return preg_replace_callback('/"([^"\\\\]*(\\\\.[^"\\\\]*)*)"|[\s]+/', function ($matches) {
            return isset($matches[1]) ? "\"{$matches[1]}\"" : '';
        }, $jsonString);
    }
}