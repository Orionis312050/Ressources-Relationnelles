// 
// CHECKBOX
// 

document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('.checkbox');
    const questionBoutton = document.getElementById('question-button');
    const questionForm = document.getElementById('question-form');
    const body = document.querySelector('body');


    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            checkboxes.forEach(otherCheckbox => {
                if (otherCheckbox !== checkbox) {
                    otherCheckbox.checked = false;
                }
            });
        });
    });

    questionBoutton.addEventListener('click', function() {
        questionForm.style.display = "flex";
        body.style.overflow = "hidden";
    });

});

// 
// FILTRAGE
// 

document.addEventListener('DOMContentLoaded', function () {
    let categoryCheckboxes = document.querySelectorAll('input[name="category"]');
    categoryCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateFilteredQuestions();
        });
    });

    function updateFilteredQuestions() {
        let selectedCategories = getSelectedCategories();
        filterByCategories(selectedCategories);
    }

    function getSelectedCategories() {
        let selectedCategories = [];
        let categoryCheckboxes = document.querySelectorAll('input[name="category"]:checked');
        categoryCheckboxes.forEach(function (checkbox) {
            selectedCategories.push(checkbox.value);
        });
        return selectedCategories;
    }

    function filterByCategories(selectedCategories) {
        let allQuestions = document.querySelectorAll('.questions > div');
        allQuestions.forEach(function (question) {
            let questionCategory = question.getAttribute('data-category');
            if (selectedCategories.length === 0 || selectedCategories.includes('all') || selectedCategories.includes(questionCategory)) {
                question.style.display = 'block';
            } else {
                question.style.display = 'none';
            }
        });
    }
});

// 
// REQÊTE AJAX
// 

// let currentPage = 1;
// let isLoading = false;

// function loadMoreQuestions() {
//     if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight && !isLoading) {
//         isLoading = true;

//         let xhr = new XMLHttpRequest();
//         xhr.open('GET', '/help?page=' + (currentPage + 1));
//         xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === XMLHttpRequest.DONE) {
//                 if (xhr.status === 200) {
//                     let data = JSON.parse(xhr.responseText);
//                     let questions = data.questions;

//                     let questionsContainer = document.querySelector('.questions-container');
//                     questions.forEach(function(question) {
//                         let questionElement = document.createElement('div');
//                         questionElement.innerHTML = `
//                             <div data-category="${question.category}">
//                                 <input type="checkbox" id="question${question.id}" class="checkbox">
//                                 <label for="question${question.id}" class="answer-label">
//                                     <p class="question">${question.question}</p>
//                                     <span class="deroulant1"></span>
//                                     <span class="deroulant2"></span>
//                                 </label>
//                                 <p class="answer">${question.answer}</p>
//                             </div>
//                         `;
//                         questionsContainer.appendChild(questionElement);
//                     });

//                     currentPage++;
//                     isLoading = false;
//                 } else {
//                     console.error('Erreur lors du chargement des questions:', xhr.status);
//                     isLoading = false;
//                 }
//             }
//         };
//         xhr.send();
//     }
// }

// window.addEventListener('scroll', loadMoreQuestions);

// loadMoreQuestions();
