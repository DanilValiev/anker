document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('change', async (event) => {
        if (!event.target.id.startsWith('Deal_dealContractors_') || !event.target.id.endsWith('_contractor')) return;
        const url = 'admin?crudAction=contractsForContractor&crudControllerFqcn=App%5CController%5CAdmin%5CDealCrudController&contractor_id=' + event.target.value;
        const contracts = await (await fetch(url)).json();
        if (contracts.length === 0) {
            console.error('No contracts for contractor ' + event.target.value);
            return;
        }
        const parentGroup = event.target.closest('.accordion-body');
        const contractInput = parentGroup.querySelector('[id^="Deal_dealContractors_"][id$="_contract"]');
        contractInput.tomselect.clear();
        contractInput.tomselect.clearOptions();
        contractInput.tomselect.addOptions(contracts);
        contractInput.tomselect.addItem(contracts[0].value);
    });
});