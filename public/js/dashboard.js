// 
// STATISTIQUES
// 


const users = document.querySelectorAll('#account-value p');
const formAccount = document.getElementById('account-form');
const body = document.querySelector('body');
const valid = document.getElementById('valid-account');
const addCat = document.getElementById('add-cat');
const btnCatForms = document.querySelectorAll('.back-cat-form');
const formCat = document.getElementById('form-cat');
const savFormBtns = document.querySelectorAll('.open-sav-form');
const savForm = document.getElementById('sav-form');
const backSavForms = document.querySelectorAll('.back-sav-form');

users.forEach((user) => {
    user.addEventListener('click', () => {
        formAccount.style.display = 'flex';
        body.style.overflow = 'hidden';
    });
});
valid.addEventListener('click', ()=> {
    formAccount.style.display = 'none';
    body.style.overflow = 'auto';
});
addCat.addEventListener('click', () => {
    formCat.style.display = "flex";
    body.style.overflow = "hidden";
});
btnCatForms.forEach((btnCatForm) => {
    btnCatForm.addEventListener('click', () => {
        formCat.style.display = "none";
        body.style.overflow = "auto";
    })
});
savFormBtns.forEach((savFormBtn) => {
    savFormBtn.addEventListener('click', () => {
        savForm.style.display = "flex";
        body.style.overflow = "hidden";
    })
});
backSavForms.forEach((backSavForm) => {
    backSavForm.addEventListener('click', () => {
        savForm.style.display = "none";
        body.style.overflow = "auto";
    })
});

// REQUÊTE AJAX

document.addEventListener('DOMContentLoaded', function() {
    var selectStats = document.getElementById('stats-select');
    selectStats.addEventListener('change', function() {
        var selectedOption = selectStats.value;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('show-user-stat').innerHTML = response.userStats;
                    document.getElementById('show-post-stat').innerHTML = response.postStats;
                    document.getElementById('show-visits-stat').innerHTML = response.visits;

                } else {
                    console.error('Une erreur est survenue lors de la requête AJAX.');
                }
            }
        };
        xhr.open('POST', '/dashboard/ajax', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('filter=' + encodeURIComponent(selectedOption));
    });
});


// 
// COMPTES
// 

document.querySelectorAll('.compte').forEach(item => {
    item.addEventListener('click', event => {
        const userId = item.getAttribute('data-user-id');
        const firstName = item.getAttribute('data-first-name');
        const lastName = item.getAttribute('data-last-name');
        const avatar = item.getAttribute('data-avatar');
        const roleId = item.getAttribute('data-roles'); 
        console.log(roleId);

        document.getElementById('account-form').innerHTML = `
            <img src="${avatar}" alt="${firstName} ${lastName}">
            <p>${firstName} ${lastName}</p>
            <select id="role-select">
                <option value="ROLE_USER" ${roleId === 'user' ? 'selected' : ''}>Citoyen</option>
                <option value="ROLE_MODERATOR" ${roleId === 'moderator' ? 'selected' : ''}>Modérateur</option>
                <option value="ROLE_ADMIN" ${roleId === 'admin' ? 'selected' : ''}>Administrateur</option>
                <option value="ROLE_SUPERADMIN" ${roleId === 'superadmin' ? 'selected' : ''}>Super-admin</option>
            </select>
            <a href="#" id="valid-account" data-user-id="${userId}" data-role-id="${roleId}">Valider</a>
        `;

        document.getElementById('role-select').addEventListener('change', event => {
            const selectedRoleId = event.target.value;
            document.getElementById('valid-account').setAttribute('data-role-id', selectedRoleId);
            const userId = document.getElementById('valid-account').getAttribute('data-user-id');
            const href = "/dashboard/role-user/" + userId + "/" + selectedRoleId;
            document.getElementById('valid-account').setAttribute('href', href);
        });

        event.preventDefault();
    });
});

// 
// CATEGORY
// 

let form = document.querySelector('#form-cat form');
let nameInput = document.querySelector('#name-cat');
let editLinks = document.querySelectorAll('#cat-value a[data-action="edit"]');
let categoryContainers = document.querySelectorAll('.categoryContainer');

editLinks.forEach(function(editLink, index) {
    editLink.addEventListener('click', function(event) {
        event.preventDefault();
        let categoryId = categoryContainers[index].dataset.id; // Récupérer l'ID de la catégorie correspondante
        let categoryName = categoryContainers[index].querySelector('p').textContent;
        nameInput.setAttribute('value', categoryName);
        console.log(nameInput);
        document.getElementById('add-cat-form').textContent = 'Modifier';
        form.action = '/dashboard/category/edit/' + categoryId;
        document.getElementById('form-cat').style.display = 'flex';
    });
});

let backButton = document.querySelector('#back-cat-form');

backButton.addEventListener('click', function(event) {
    event.preventDefault();
    form.reset();
    document.getElementById('add-cat-form').textContent = 'Ajouter';
    form.action = '{{ path("app_dashboard_add_category") }}';
    document.getElementById('form-cat').style.display = 'none';
    body.style.overflow = "auto";
});

nameInput.addEventListener('input', function() {
    nameInput.setAttribute('value', nameInput.value);
});


// 
// SAV
// 

document.addEventListener("DOMContentLoaded", function() {
    let editButtons = document.querySelectorAll(".open-sav-form");
    editButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            let savForm = document.querySelector("#sav-form textarea");
            let questionContainer = event.target.closest(".question-container");
            let question = questionContainer.querySelector("p").textContent.trim();
            let existingQuestion = document.getElementById('existinQuestion');
            existingQuestion.innerHTML = question;

            let answerContainer = event.target.closest(".answer-container");
            let answer = answerContainer.querySelector(".answer");
            if (answer !== null && answer !== undefined) {
                answer = answer.textContent.trim();
                savForm.innerHTML = answer;
            };

            let questionId = questionContainer.querySelector('p').dataset.id;
            let form = document.querySelector("#sav-form form");
            form.setAttribute('action', "/dashboard/help/answer/"+questionId);

            savForm.addEventListener("input", function() {
                savForm.innerHTML = savForm.value;
            })
        });
    });
});


// 
// SEARCH BAR
// 

let inputs = document.querySelectorAll('.searchbar');
let listsContainer = document.querySelectorAll('.lists');

inputs.forEach(function(input) {
    let filter = input.getAttribute('data-filter');
    let idContainer = filter + "-value";
    let listContainer = document.getElementById(idContainer);
    input.addEventListener('input', function() {
        searchBar(input.value.trim().toLowerCase(), listContainer, idContainer);
    });
})

function searchBar(input, listContainer, idContainer) {
    let items = listContainer.querySelectorAll('#' + idContainer + ' > div');
    items.forEach(function(item) {
        for (let i = 0; i < items.length; i++) {
            let name = item.querySelector('.post-title').textContent.trim().toLowerCase();
            if (name.includes(input)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        }
    });
}