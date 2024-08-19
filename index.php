<html>

<head>
    <title>SSE Demo</title>
</head>

<body>
    <div class="flex justify-between" style="gap: 2em">
        <div>
            <div>
                <h4>Чат</h4>
            </div>
            <div>
                <ul id="chat"></ul>
            </div>
        </div>
        <div>
            <div>
                <h4 class="text-info">Информация</h4>
            </div>
            <ul id="info"></ul>
        </div>
        <div class="text-error">
            <div>
                <h4>Информация об ошибках</h4>
            </div>
            <div id="error"></div>
        </div>
    </div>
</body>

<script>
    const chatNode = document.querySelector('#chat')
    const infoNode = document.querySelector('#info')
    const errorNode = document.querySelector('#error')

    // коннектимся постоянным http соединением к бесконечному скрипту сервера
    const es = new EventSource('/sse.php')
    // событие при установке соединения
    es.onopen = (e) => {
        const node = document.createElement('li')
        node.classList = 'text-success'
        node.innerText = 'Соединение установлено'
        infoNode.appendChild(node)
    }
    // событие на кастомный ивент info
    es.addEventListener('info', e => {
        const data = JSON.parse(e.data)
        const node = document.createElement('li')
        node.classList = 'text-info'
        node.innerText = data.message
        infoNode.appendChild(node)
    })
    // стандартное событие типа message
    es.onmessage = (e) => {
        const data = JSON.parse(e.data)
        const node = document.createElement('li')
        node.innerHTML = `<b>${data.whom}:</b> <span>${data.message}</span>`
        chatNode.appendChild(node)
    }
    // событие ошибки
    es.onerror = (e) => {
        const data = JSON.parse(e.data)
        errorNode.innerText = data.message
    }
</script>

<style>
    ul {
        list-style: none;
        padding: 0;
    }

    .flex {
        display: flex;
    }
    .justify-between {
        justify-content: space-between;
    }
    .items-center {
        align-items: center;
    }

    .text-success {
        color: green;
    }
    .text-info {
        color: lightblue;
    }
    .text-error {
        color: red;
    }
</style>

</html>