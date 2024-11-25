<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List actualizable mediante botón</title>
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Contenedor principal para centrar todo el contenido -->
    <div class="container">
        <!-- Título principal de la aplicación de lista de tareas -->
        <h2>TODO List</h2>

        <!-- Campo de entrada para nueva tarea -->
        <label for="content">Nueva tarea</label>
        <input type="text" id="content" placeholder="Ingresa una tarea"><br>

        <!-- Botón para guardar la nueva tarea -->
        <button id="guardar">Guardar</button>

        <?php
        require "DB.php";
        require "todo.php";
        try {
            $db = new DB;
            echo "<h2>TODO</h2>";
            echo "<ul id=\"lista\">";

            // Obtener la lista de tareas desde la base de datos
            $todo_list = Todo::DB_selectAll($db->connection);
            foreach ($todo_list as $row) {
                // Cada elemento de la lista con un botón para borrar
                echo "<li id='item-" . $row->getItem_id() . "'>" .
                    "<span class='task-content'>" . $row->getItem_id() . ". " . htmlspecialchars($row->getContent()) . "</span>" .
                    " <button onclick='editar(" . $row->getItem_id() . ")'>Editar</button>" .
                    " <button onclick='borrar(" . $row->getItem_id() . ")'>X</button>" .
                    "</li>";

            }
            echo "</ul>";
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        ?>
    </div>

    <script>
        /**
        * Envía una solicitud al controlador con el método y datos proporcionados.
        * @param {string} metodo - Método HTTP (POST, DELETE, PUT).
        * @param {Object} postData - Datos a enviar en el cuerpo de la solicitud.
        */
        function llamada_a_controller(metodo, postData) {
            const url = 'http://todo.terminus.lan/controller.php';
            // Limpiar la tabla antes de agregar los nuevos datos
            const lista = document.getElementById('lista');
            lista.innerHTML = ''; // Eliminar el contenido previo de la tabla

            // La respuesta es un array de objetos JSON
            fetch(url, {
                method: metodo,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postData)
            })
                .then(response => response.json())  // Convertir la respuesta a JSON
                .then(data => {
                    // Reconstruir la lista de tareas
                    data.forEach(item => {
                        const li = document.createElement("li");
                        li.id = `item-${item.item_id}`;
                        const span = document.createElement("span");
                        span.className = "task-content";
                        span.textContent = `${item.item_id}. ${item.content}`;

                        const editButton = document.createElement("button");
                        editButton.textContent = "Editar";
                        editButton.onclick = () => editar(item.item_id);

                        const deleteButton = document.createElement("button");
                        deleteButton.textContent = "X";
                        deleteButton.onclick = () => borrar(item.item_id);

                        li.appendChild(span);
                        li.appendChild(editButton);
                        li.appendChild(deleteButton);
                        lista.appendChild(li);
                    });
                })
                .catch(error => console.error('Error en la solicitud:', error));
        }

        /**
         * Permite editar el contenido de una tarea específica.
         * @param {number} item_id - ID del elemento a editar.
         */
        function editar(item_id) {
            const item = document.getElementById(`item-${item_id}`);
            const content = item.querySelector(".task-content").textContent;

            const newContent = prompt("Edita la tarea:", content);
            if (newContent !== null && newContent.trim() !== "") {
                const postData = { item_id: item_id, content: newContent };
                llamada_a_controller("PUT", postData);
            }
        }

        /**
        * Borra una tarea específica de la lista.
        * @param {number} item_id - ID del elemento a borrar.
        */
        function borrar(item_id) {
            const postData = {
                item_id: item_id
            };
            llamada_a_controller("DELETE", postData);
        }

        // Evento para guardar una nueva tarea
        document.getElementById('guardar').addEventListener('click', function () {
            const contenido = document.getElementById('content').value;
            if (!contenido) {
                alert('Por favor, introduce un valor.');
                return;
            }

            const postData = {
                content: contenido
            };
            llamada_a_controller("POST", postData);
        });
    </script>

</body>

</html>