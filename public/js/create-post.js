let elementCount = 0; // Variable to keep track of the number of elements
const addTextBtn = document.getElementById('add-text-btn');
const addImageBtn = document.getElementById('add-image-btn');
const containerDiv = document.getElementById('create-post');
const imageInput = document.getElementById('image-input');

const titleInput = document.getElementById('title');
const descInput = document.getElementById('description');
const catSelect = document.getElementById('category');
const addressInput = document.getElementById('address');

let paragraphDataArray = [];
let imageDataArray = [];
let allElementData = [];

function dragMoveListener(event) {
    let target = event.target;
    let x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
    let y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

    // target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
    target.style.position = 'absolute';
    target.style.top = y + 'px';
    target.style.left = x + 'px';

    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
    mettreAJourElementData();
}

window.dragMoveListener = dragMoveListener;

interact('.resize-drag')
    .resizable({
        edges: { left: true, right: true, bottom: true, top: true },

        listeners: {
            move(event) {
                let target = event.target;
                let x = (parseFloat(target.getAttribute('data-x')) || 0);
                let y = (parseFloat(target.getAttribute('data-y')) || 0);

                target.style.width = event.rect.width + 'px';
                target.style.height = event.rect.height + 'px';

                x += event.deltaRect.left;
                y += event.deltaRect.top;

                // target.style.transform = 'translate(' + x + 'px,' + y + 'px)';
                target.style.position = 'absolute';
                target.style.top = y + 'px';
                target.style.left = x + 'px';

                target.setAttribute('data-x', x);
                target.setAttribute('data-y', y);
            },
            end(event) {
                mettreAJourElementData();
            }
        },
        modifiers: [
            interact.modifiers.restrictEdges({
                outer: 'parent'
            }),

            interact.modifiers.restrictSize({
                min: { width: 100, height: 50 }
            })
        ],

        inertia: true
    })
    .draggable({
        listeners: { move: window.dragMoveListener },
        inertia: true,
        modifiers: [
            interact.modifiers.restrictRect({
                restriction: 'parent',
                endOnly: true
            })
        ]
    });

interact('.dropzone').dropzone({
    accept: '.drag-drop',
    overlap: 0.25,

    ondropactivate: function (event) {
        event.target.classList.add('drop-active');
    },
    ondrop: function (event) {
        let draggableElement = event.relatedTarget;
        draggableElement.parentNode.removeChild(draggableElement);
        let imageName = draggableElement.querySelector('img').getAttribute('data-name'); // Nom de l'image à supprimer
        deleteImage(imageName);
    }
});

addTextBtn.addEventListener('click', () => {
    const newDiv = document.createElement("div");
    newDiv.classList.add("resize-drag");
    newDiv.classList.add("drag-drop");
    newDiv.classList.add("editor");

    newDiv.setAttribute('data-x', 0);
    newDiv.setAttribute('data-y', 0);
    newDiv.setAttribute('id', null);

    containerDiv.appendChild(newDiv);

    initializeQuillForElement(newDiv);
});

addImageBtn.addEventListener('click', () => {
    imageInput.click();
});

imageInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const randomName = Math.floor(Math.random() * 1000000000);
        const newDiv = document.createElement("div");
        newDiv.classList.add("resize-drag");
        newDiv.classList.add("drag-drop");
        newDiv.classList.add("drag");

        newDiv.setAttribute('data-x', 0);
        newDiv.setAttribute('data-y', 0);
        newDiv.setAttribute('id', null);

        const newImage = document.createElement("img");
        const imageName = '/images/post/' + randomName + '.' + file.name.split('.').pop(); // Nom de fichier avec le nom aléatoire
        newImage.src = URL.createObjectURL(file);
        newImage.setAttribute('data-name', imageName); // Stocker le nom aléatoire dans l'attribut data
        newDiv.appendChild(newImage);
        containerDiv.appendChild(newDiv);
        uploadImage(file, imageName);
        mettreAJourElementData();
    }
});

const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike', 'link'],
    [{ 'align': [] }],
    [{ 'color': [] }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'header': [1, 2, 3, false] }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }]
];

function initializeQuillForElement(element) {
    const quill = new Quill(element, {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'bubble'
    });

    quill.on('text-change', () => {
        mettreAJourElementData();
    });
}

titleInput.addEventListener('input', mettreAJourElementData);
descInput.addEventListener('input', mettreAJourElementData);
catSelect.addEventListener('change', mettreAJourElementData);
addressInput.addEventListener('input', mettreAJourElementData);

function mettreAJourElementData() {
    const elements = document.querySelectorAll('.resize-drag');
    imageDataArray = [];
    paragraphDataArray = [];
    allElementData = [];
    
    let title = titleInput.value;
    let desc = descInput.value;
    let cat = catSelect.value;
    let address = addressInput.value

    let ressouceArray = {
        type: "parametre",
        title: title,
        description: desc,
        category: cat,
        address: address
    };
    
    elements.forEach(element => {
        let x = (parseFloat(element.getAttribute('data-x')) || 0);
        let y = (parseFloat(element.getAttribute('data-y')) || 0);

        if (element.classList.contains('resize-drag')) {
            let imageElement = element.querySelector('img');
            if (imageElement) {
                let imageId = imageElement.id
                let src = imageElement.getAttribute('data-name');
                let imageElementData = {
                    type: 'image',
                    id: imageId,
                    x: x,
                    y: y,
                    src: src,
                    width: imageElement.offsetWidth,
                    height: imageElement.offsetHeight
                };
                imageDataArray.push(imageElementData);
            } else {
                let ps = element.querySelectorAll('.ql-editor');
                ps.forEach(p => {
                    let pId = p.id
                    let content = p.innerHTML;
                    let paragraphElementData = {
                        type: 'paragraph',
                        id: pId,
                        x: x,
                        y: y,
                        content: content,
                        width: element.offsetWidth,
                        height: element.offsetHeight
                    };
                    paragraphDataArray.push(paragraphElementData);
                });
            }
        }
    });

    // Combiner les données en un seul tableau
    allElementData = [ressouceArray, ...paragraphDataArray, ...imageDataArray];
    console.log(allElementData);
    
    // Convertir allElementData en chaîne JSON
    const dataInput = document.getElementById("data_input");
    dataInput.value = JSON.stringify(allElementData);
}

function uploadImage(file, imageName) {
    let formData = new FormData();
    formData.append('image-name', imageName);
    formData.append('file', file);
    console.log("formData",formData);
    console.log("imageName",imageName);
    console.log("file",file);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/modification-post/upload', true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Image uploaded successfully.');
            } else {
                console.error('Error uploading image.');
            }
        }
    };

    xhr.send(formData);
}

function deleteImage(imageName) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/modification-post/delete', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Image deleted successfully.');
            } else {
                console.error('Error deleting image.');
            }
        }
    };

    let data = JSON.stringify({ imageName: imageName }); // Envoyer le nom de l'image à supprimer
    xhr.send(data);
}