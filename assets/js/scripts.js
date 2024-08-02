document.addEventListener('DOMContentLoaded', function() {
    function updateClassList() {
        fetch('fetch_classes.php')
            .then(response => response.json())
            .then(data => {
                const classList = document.querySelector('#class-list');
                classList.innerHTML = '';
                data.forEach(classItem => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.innerHTML = `
                        ${classItem.class_name} (${classItem.class_code})
                        <button class="btn btn-danger" onclick="deleteClass(${classItem.id})">Delete</button>
                    `;
                    classList.appendChild(listItem);
                });
            });
    }

    updateClassList();

    window.deleteClass = function(classId) {
        if (confirm('Are you sure you want to delete this class?')) {
            fetch('delete_class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ class_id: classId })
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                updateClassList();
            });
        }
    };
});
