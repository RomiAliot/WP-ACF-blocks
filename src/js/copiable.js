export const initCopiable = () => {
    const copiableElements = document.querySelectorAll('[data-clipboard-text]');
    if (!copiableElements.length) return;

    copiableElements.forEach(element => {
        const copiableInnerText = element.querySelector('.button__inner-text');
        const initialText = copiableInnerText.innerText;
        element.addEventListener('click', () => {
            const text = element.getAttribute('data-clipboard-text');
            navigator.clipboard.writeText(text).then(() => {
                copiableInnerText.innerText = 'Copied!';
                setTimeout(() => {
                    copiableInnerText.innerText = initialText;
                }, 2000);
            });
        });
    })


}