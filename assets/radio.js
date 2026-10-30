(function() {
    'use strict';

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeRadios);
    } else {
        initializeRadios();
    }

    function initializeRadios() {
        document.querySelectorAll('.yii2-radio').forEach(container => {
            const radio = container.querySelector('.simple-radio');
            if (radio && !radio.hasAttribute('data-radio-initialized')) {
                radio.setAttribute('data-radio-initialized', 'true');
            }
        });
    }

    document.addEventListener('click', handleRadioClick);

    function handleRadioClick(e) {
        const radio = e.target.closest('.simple-radio');
        if (!radio) return;

        // Always prevent the anchor from navigating.
        e.preventDefault();

        // Do nothing if already selected
        if (radio.dataset.checked === 'true') return;

        const container = radio.closest('.yii2-radio');
        if (!container) return;

        const url   = radio.dataset.url;
        const name  = container.dataset.radioName;
        const value = radio.dataset.value;

        setLoadingState(radio, true);

        const body = new URLSearchParams();
        body.append(name, value);

        fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin',
            body: body.toString()
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error. status: ${response.status}`);
            }
            return response.json().catch(() => ({}));
        })
        .then(() => {
            updateRadioGroupUI(container, value);
        })
        .catch(error => {
            console.error('Radio update failed:', error);
            alert('Update failed. Please try again.');
        })
        .finally(() => {
            setLoadingState(radio, false);
        });
    }

    function updateRadioGroupUI(container, selectedValue) {
        container.querySelectorAll('.simple-radio').forEach(radio => {
            const isSelected = String(radio.dataset.value) === String(selectedValue);
            const icon       = radio.querySelector('i');

            radio.dataset.checked = isSelected ? 'true' : 'false';
            radio.setAttribute('aria-checked', isSelected ? 'true' : 'false');
            radio.classList.toggle('radio-checked', isSelected);

            const iconClass  = isSelected ? radio.dataset.checkedIcon   : radio.dataset.uncheckedIcon;
            const colorClass = isSelected ? radio.dataset.checkedColor  : radio.dataset.uncheckedColor;
            const iconSize   = radio.dataset.iconSize;

            icon.className = `${iconClass} ${iconSize} ${colorClass} align-middle`;
        });

        container.dataset.radioValue = selectedValue;
    }

    function setLoadingState(radio, isLoading) {
        if (isLoading) {
            radio.style.pointerEvents = 'none';
            radio.classList.add('switch-loading');
        } else {
            radio.style.pointerEvents = '';
            radio.classList.remove('switch-loading');
        }
    }
})();
