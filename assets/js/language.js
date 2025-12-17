function getCurrentLanguage() {
    return localStorage.getItem('hotelLanguage') || 'en';
}

function setLanguage(lang) {
    localStorage.setItem('hotelLanguage', lang);
}

function changeLanguage(lang) {
    setLanguage(lang);
    applyTranslations(lang);
}

function applyTranslations(lang) {
    const translation = translations[lang] || translations['en'];
    
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translation[key]) {
            element.textContent = translation[key];
        }
    });
    
    document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
        const key = element.getAttribute('data-translate-placeholder');
        if (translation[key]) {
            element.placeholder = translation[key];
        }
    });
}

function initLanguage() {
    const currentLang = getCurrentLanguage();
    
    const languageSelect = document.getElementById('languageSelect');
    if (languageSelect) {
        languageSelect.value = currentLang;
    }
    
    applyTranslations(currentLang);
}

document.addEventListener('DOMContentLoaded', initLanguage);