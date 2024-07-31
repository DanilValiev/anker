function setSlugValue(value) {
    const antifraudMerchantSlug = document.querySelector('[data-value=antifraudMerchantSlug]');
    if (antifraudMerchantSlug === null) return;
    const row = antifraudMerchantSlug.closest('.row');
    const slugInput = row.querySelector('[id^="Boarding_settings_"][id$="_value"]');
    slugInput.value = value;
}
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('change', async (event) => {
        if (event.target.id !== 'Boarding_contractor') return;
        const url = 'admin?crudAction=contractorSlug&crudControllerFqcn=App%5CController%5CAdmin%5CMerchantBoardingCrudController&contractor_id=' + event.target.value;
        setSlugValue(await (await fetch(url)).text());
    });
    document.addEventListener('input', async (event) => {
        if (event.target.id !== 'Boarding_contractor_slug') return;
        setSlugValue(event.target.value);
    });
});