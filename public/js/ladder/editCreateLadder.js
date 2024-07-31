function addListenersToRadios() {
    const radios = document.querySelectorAll('input[type=checkbox]');

    for (const clickedRadio of radios) {
        clickedRadio.addEventListener('change', () => {
            for (const otherRadio of radios) {
                if (otherRadio.id !== clickedRadio.id) {
                    otherRadio.checked = false;
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    addListenersToRadios();
    document.addEventListener('ea.collection.item-added', addListenersToRadios);
});