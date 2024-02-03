document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.acc-dow').addEventListener('click', function() {
            var userDrop = document.querySelector('.user-drop');
            userDrop.style.display = (userDrop.style.display === 'block') ? 'none' : 'block';
        });
    });
