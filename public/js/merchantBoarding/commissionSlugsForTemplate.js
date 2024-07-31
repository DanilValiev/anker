document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('change', async (event) => {
        if (event.target.id !== 'Boarding_templateProduct') return;
        const url = 'admin?crudAction=commissionSlugsForTemplate&crudControllerFqcn=App%5CController%5CAdmin%5CMerchantBoardingCrudController&template_product_id=' + event.target.value;
        const slugs = await (await fetch(url)).json();
        if (slugs.length === 0) {
            console.warn('No commissionSlugs for template_product ' + event.target.value);
            return;
        }
        const deleteButtons = document.querySelectorAll('.commission .field-collection-delete-button');
        for (const db of deleteButtons) {
            db.dispatchEvent(new Event('click'));
        }
        const addButton = document.querySelector('.commission .field-collection-add-button');
        for (let i = 1; i <= slugs.length; i++) {
            addButton.dispatchEvent(new Event('click'));
        }
        const slug_htmls = document.querySelectorAll('[id^="Boarding_commissions_"][id$="_slug"]');
        for (let i = 0; i < slugs.length; i++) {
            slug_htmls[i].value = slugs[i];
        }
    });
});