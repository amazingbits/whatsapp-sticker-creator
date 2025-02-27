{% extends "_template.twig.php" %}
{% block content %}
<form id="addText">
    <div class="input-group">
        <label for="text">Adicione um texto à figurinha</label>
        <input type="text" name="text" id="text" placeholder="Ex: olá, mundo!">
    </div>
    <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png, .gif">
    <button type="submit">Adicionar</button>
</form>
<div class="action-buttons">
    <label for="image">Adicionar imagem</label>
    <a href="{{ BASE_URL }}">Reiniciar</a>
</div>
<canvas id="canvas" width="600" height="600"></canvas>
<div class="action-buttons">
    <button id="downloadImageButton">Salvar figurinha</button>
    <button id="sendViaWhatsappButton">Enviar via Whatsapp</button>
</div>
<div class="input-group">
    <label for="phoneNumber">Número de telefone para enviar a figurinha</label>
    <input type="text" name="phoneNumber" id="phoneNumber">
</div>
{% endblock %}

{% block customJS %}
<script src="{{ BASE_URL }}/assets/js/fabric.js"></script>
<script src="{{ BASE_URL }}/assets/js/fontfaceobserver.js"></script>

<script>
    const canvas = new fabric.Canvas("canvas");
    const image = document.querySelector("#image");
    const form = document.querySelector("#addText");
    const downloadImageButton = document.querySelector("#downloadImageButton");
    const sendViaWhatsappButton = document.querySelector("#sendViaWhatsappButton");

    const textStyle = {
        fontFamily: "Anton, sans-serif",
        fontSize: 70,
        fill: "#FFFFFF",
        stroke: "#000000",
        strokeWidth: 1,
        fontWeight: "bold",
        textAlign: "center",
        shadow: "4px 4px 8px rgba(0,0,0,0.4)",
    };

    async function loadFonts() {
        const anton = new FontFaceObserver("Anton");
        await anton.load();
    }

    function generateRandomNumber(min, max) {
        return Math.floor(Math.random() * max) + min;
    }

    function generateId(prefix = "", moreEntropy = false) {
        const base = Date.now().toString(36) + Math.random().toString(36).substr(2, 5);
        return prefix + (moreEntropy ? base + "." + Math.floor(Math.random() * 1000000) : base);
    }

    function getRandomScreenPosition() {
        const maxSize = 500;
        const top = generateRandomNumber(1, maxSize);
        const left = generateRandomNumber(1, maxSize);
        return {top, left};
    }

    async function insertText(text) {
        await loadFonts();
        const randomPosition = getRandomScreenPosition();
        const txt = new fabric.Text(text, {...textStyle, ...randomPosition});
        canvas.add(txt);
        canvas.bringToFront(txt);
        canvas.discardActiveObject().renderAll();
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const text = document.querySelector("input[name='text']");
        if (text.value.trim() !== "") {
            await insertText(text.value.trim());
            text.value = "";
        }
    });

    function getFileExtension(file) {
        return file.name.includes(".") ? file.name.split(".").pop().toLowerCase() : "";
    }

    function insertImage(file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            const randomPosition = getRandomScreenPosition();
            fabric.Image.fromURL(event.target.result, (img) => {
                img.set({...randomPosition, scaleX: 0.5, scaleY: 0.5});
                canvas.add(img);
                canvas.bringToFront(img);
                canvas.getObjects("text").forEach(text => canvas.bringToFront(text));
                canvas.discardActiveObject().renderAll();
            });
        }
        reader.readAsDataURL(file);
    }

    image.addEventListener("change", async (event) => {
        const fileList = event.target.files;
        if (fileList.length > 0) {
            const file = fileList[0];
            const allowedExtensions = ["png", "jpg", "jpeg", "gif"];
            const fileExtension = getFileExtension(file);
            if (!allowedExtensions.includes(fileExtension)) {
                alert(`A extensão ${fileExtension} não é permitida. \n\nExtensões permitidas: ${allowedExtensions.join(", ")}`);
                return;
            }
            insertImage(file);

            image.value = "";
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Delete" || event.key === "Backspace") {
            const activeElement = canvas.getActiveObject();
            if (activeElement) {
                canvas.remove(activeElement);
                canvas.discardActiveObject().renderAll();
            }
        }
    });

    canvas.on("mouse:up", (event) => {
        setTimeout(() => {
            canvas.discardActiveObject();
            canvas.renderAll();
        }, 2000);
    });

    function downloadImage() {
        const dataURL = canvas.toDataURL({format: "png"});
        const link = document.createElement("a");
        link.href = dataURL;
        link.download = `${generateId("sticker_")}.png`;
        link.click();
    }

    downloadImageButton.addEventListener("click", (event) => {
        event.preventDefault();
        downloadImage();
    });

    sendViaWhatsappButton.addEventListener("click", async (event) => {
        event.preventDefault();
        const url = "{{ BASE_URL }}/api/whatsapp/send";
        const method = "POST";
        const phoneNumber = document.querySelector("#phoneNumber");
        if (phoneNumber.value.trim() === "") {
            alert("Insira um número de telefone");
            return;
        }
        const base64Image = canvas.toDataURL({format: "png"});
        const blob = await fetch(base64Image).then(res => res.blob());
        const image = new File([blob], "image.png", {type: "image/png"});
        const body = new FormData();
        body.append("phoneNumber", phoneNumber.value);
        body.append("image", image);
        try {
            sendViaWhatsappButton.disabled = true;
            const request = await fetch(url, {method, body});
            const response = await request.json();
            alert(response.message);
        } catch (error) {
            console.log(error);
            alert("Houve um problema ao enviar sua imagem");
        } finally {
            sendViaWhatsappButton.disabled = false;
        }
    });
</script>
{% endblock %}