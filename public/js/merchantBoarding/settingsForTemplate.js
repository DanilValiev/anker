function getAntifraudSlug() {
    const antifraudMerchantSlug = document.querySelector('[data-value=antifraudMerchantSlug]');
    if (antifraudMerchantSlug === null) return;
    const row = antifraudMerchantSlug.closest('.row');
    return row.querySelector('[id^="Boarding_settings_"][id$="_value"]') ?? {value:0};
}

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('change', async (event) => {
        if (event.target.id !== 'Boarding_templateProduct') return;
        const url = 'admin?crudAction=settingsForTemplate&crudControllerFqcn=App%5CController%5CAdmin%5CMerchantBoardingCrudController&template_product_id=' + event.target.value;
        const settings = await (await fetch(url)).json();
        if (settings.length === 0) {
            console.warn('No settings for template_product ' + event.target.value);
            return;
        }
        const antifraudSlugValue = getAntifraudSlug().value;
        const deleteButtons = document.querySelectorAll('.settings .field-collection-delete-button');
        for (const db of deleteButtons) {
            db.dispatchEvent(new Event('click'));
        }
        const addButton = document.querySelector('.settings .field-collection-add-button');
        for (let i = 1; i <= settings.length; i++) {
            addButton.dispatchEvent(new Event('click'));
        }
        const setting_htmls = document.querySelectorAll('[id^="Boarding_settings_"][id$="_key"]');
        for (let i = 0; i < settings.length; i++) {
            setting_htmls[i].tomselect.clearActiveItems();
            setting_htmls[i].tomselect.addItem(settings[i]);
        }
        getAntifraudSlug().value = antifraudSlugValue;
    });
});