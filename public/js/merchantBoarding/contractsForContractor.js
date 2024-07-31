document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('change', async (event) => {
        if (event.target.id !== 'Boarding_contractor') return;
        const url = 'admin?crudAction=contractsForContractor&crudControllerFqcn=App%5CController%5CAdmin%5CMerchantBoardingCrudController&contractor_id=' + event.target.value;
        const contracts = await (await fetch(url)).json();
        if (contracts.length === 0) {
            console.warn('No contracts for contractor ' + event.target.value);
            return;
        }
        const contractInput = document.querySelector('#Boarding_contract');
        contractInput.tomselect.clear();
        contractInput.tomselect.clearOptions();
        contractInput.tomselect.addOptions(contracts);
        contractInput.tomselect.addItem(contracts[0].value);
    });
});