<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File dengan Progress Bar</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .upload-container {
            width: 400px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        #progress-container {
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }

        #progress-bar {
            height: 20px;
            width: 0%;
            background-color: #4caf50;
            text-align: center;
            color: white;
            border-radius: 5px;
            line-height: 20px;
            font-weight: bold;
        }

        .progress-text {
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
            color: #d9534f;
        }
    </style>
</head>

<body>

    <div class="upload-container">
        <input type="file" id="fileInput">
        <button id="uploadBtn">UPLOAD</button>
        <div id="progress-container">
            <div id="progress-bar">0%</div>
        </div>
        <p id="remaining-size">Sisa Limit: 1024 MB</p>
    </div>

    <script>
        let MAX_SIZE = 1024 * 1024 * 1024; // 1GB

        $("#fileInput").on("change", function () {
            let file = this.files[0];
            if (!file) return;

            if (file.size > MAX_SIZE) {
                alert("File terlalu besar! Maksimum yang tersisa: " + (MAX_SIZE / (1024 * 1024)).toFixed(2) + " MB");
                $("#fileInput").val("");
                return;
            }
        });

        $("#uploadBtn").on("click", function () {
            let file = $("#fileInput")[0].files[0];
            if (!file) {
                alert("Pilih file terlebih dahulu!");
                return;
            }

            let formData = new FormData();
            formData.append("file", file);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "upload.php", true);

            xhr.upload.onprogress = function (event) {
            };

            xhr.onload = function () {
                if (xhr.status == 200) {
                    alert("Upload berhasil!");

                    // Update MAX_SIZE setelah upload sukses
                    MAX_SIZE -= file.size;
                    let usedSize = 1024 * 1024 * 1024 - MAX_SIZE;
                    let progress = (usedSize / (1024 * 1024 * 1024)) * 100;

                    // Update tampilan progress dan sisa limit
                    $("#remaining-size").text("Sisa Limit: " + (MAX_SIZE / (1024 * 1024)).toFixed(2) + " MB");
                    $("#progress-bar").css("width", progress + "%").text(progress.toFixed(2) + "%");
                } else {
                    alert("Terjadi kesalahan saat upload!");
                }
            };

            xhr.send(formData);
        });
    </script>

</body>

</html>
