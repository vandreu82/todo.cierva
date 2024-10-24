<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List actualizble mediante botón</title>
</head>
<body>
    <label for="content">Nueva tarea</label>
    <input type="text" id="content" placeholder="Ingresa una tarea"><br>
    <button id="guardar">Guardar</button>
    <?php
        require "DB.php";
        require "todo.php";
        try {
                $db = new DB;
                echo "<h2>TODO</h2>";
                echo "<ul id=\"lista\">";
                $todo_list = Todo::DB_selectAll($db->connection);
                foreach ($todo_list as $row) {
                    echo "<li>". $row->getItem_id() .". ". $row->getContent() . "</li>";
                }
                echo "</ul>";
            } 
        catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
    ?>
    <script>
        document.getElementById('guardar').addEventListener('click', function () {
            const contenido = document.getElementById('content').value;

            if (!contenido) {
                alert('Por favor, introduce un valor.');
                return;
            }

            const url = 'http://todo.cierva/controller.php';  
            
            const postData = {
                content: contenido
            };

            // Llamada POST
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postData)
            })
            .then(response => response.json())  // Convertir la respuesta a JSON
            .then(data => {
                // Limpiar la tabla antes de agregar los nuevos datos
                const lista = document.getElementById('lista');
                lista.innerHTML = ''; // Eliminar el contenido previo de la tabla

                // La respuesta es un array de objetos JSON
                data.forEach(item => {
                    var li = document.createElement("li");
                    li.appendChild(document.createTextNode(item.item_id+". "+item.content));
                    lista.appendChild(li);
                });
            })
            .catch(error => console.error('Error en la solicitud POST:', error));
        });
    </script>

</body>
</html>