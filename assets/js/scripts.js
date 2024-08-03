document.addEventListener('DOMContentLoaded', function() {
    function updateClassList() {
        fetch('fetch_classes.php')
            .then(response => response.json())
            .then(data => {
                const classList = document.querySelector('#class-list');
                classList.innerHTML = '';
                data.forEach(classItem => {
                    const listItem = document.createElement('li');
                    listItem.className = 'bg-gray-800 mb-2 rounded p-4 flex justify-between items-center';
                    listItem.innerHTML = `
                        <a href="view_classes.php?class_id=${classItem.id}" class="text-white">${classItem.class_name} (${classItem.class_code})</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteClass(${classItem.id})">Delete</button>
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
