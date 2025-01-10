<form action="save_product.php" method="POST" enctype="multipart/form-data">
    <div style="margin-bottom: 15px;">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" maxlength="500" oninput="updateLinkToProduct()" required
            style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" maxlength="500" required
            style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="description_product">Description:</label><br>
        <textarea id="description_product" name="description_product" rows="4" required
            style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="src_image_cover">Upload Cover Image:</label><br>
        <input type="file" id="src_image_cover" name="src_image_cover" accept="image/*" onchange="previewCoverImage()"
            required style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
        <div id="preview-cover" style="margin-top: 10px;"></div>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="src_images">Upload Product Images:</label><br>
        <input type="file" id="src_images" accept="image/*" multiple onchange="addFiles()"
            style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
        <div id="preview" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px;"></div>
    </div>

    <button type="submit"
        style="background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">
        Save Product
    </button>
</form>




<script>
    const selectedFiles = [];
    const preview = document.getElementById("preview");
    const previewCover = document.getElementById("preview-cover");

    function updateLinkToProduct() {
        const nameField = document.getElementById("name");
        const linkToProductField = document.getElementById("link_to_product");
        const linkValue = nameField.value.trim().replace(/\s+/g, "_") + ".php";
        linkToProductField.value = linkValue.toLowerCase();
    }

    function showError(message) {
        alert(message);
    }

    function previewCoverImage() {
        const input = document.getElementById("src_image_cover");
        previewCover.innerHTML = ""; // ล้างภาพเก่าใน Preview

        const file = input.files[0];
        if (!file) return;

        if (!file.type.startsWith("image/")) {
            showError("Only image files are allowed.");
            input.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const container = document.createElement("div");
            container.style.position = "relative";

            const img = document.createElement("img");
            img.src = e.target.result;
            img.style.maxWidth = "150px";

            const removeButton = document.createElement("button");
            removeButton.textContent = "X";
            removeButton.style.position = "absolute";
            removeButton.style.top = "0";
            removeButton.style.right = "0";
            removeButton.style.backgroundColor = "red";
            removeButton.style.color = "white";
            removeButton.style.border = "none";
            removeButton.style.borderRadius = "50%";
            removeButton.style.cursor = "pointer";

            removeButton.onclick = () => {
                input.value = ""; // รีเซ็ต input
                previewCover.innerHTML = ""; // ล้าง preview
            };

            container.appendChild(img);
            container.appendChild(removeButton);
            previewCover.appendChild(container);
        };
        reader.readAsDataURL(file);
    }

    function addFiles() {
        const input = document.getElementById("src_images");
        const files = input.files;

        Array.from(files).forEach((file) => {
            if (!file.type.startsWith("image/")) {
                showError("Only image files are allowed.");
                return;
            }

            if (selectedFiles.some((f) => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified)) {
                showError(`File "${file.name}" is already added.`);
                return;
            }

            selectedFiles.push(file);

            const reader = new FileReader();
            reader.onload = (e) => {
                const container = document.createElement("div");
                container.style.position = "relative";

                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.maxWidth = "100px";
                img.style.margin = "5px";

                const removeButton = document.createElement("button");
                removeButton.textContent = "X";
                removeButton.style.position = "absolute";
                removeButton.style.top = "0";
                removeButton.style.right = "0";
                removeButton.style.backgroundColor = "red";
                removeButton.style.color = "white";
                removeButton.style.border = "none";
                removeButton.style.borderRadius = "50%";
                removeButton.style.cursor = "pointer";

                removeButton.onclick = () => {
                    const index = selectedFiles.findIndex(
                        (f) => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
                    );
                    if (index > -1) {
                        selectedFiles.splice(index, 1);
                    }
                    container.remove();
                };

                container.appendChild(img);
                container.appendChild(removeButton);
                preview.appendChild(container);
            };
            reader.readAsDataURL(file);
        });

        input.value = ""; // รีเซ็ต input เพื่อเลือกไฟล์ใหม่ได้
    }

    document.querySelector("form").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData();

        // เพิ่มข้อมูลฟิลด์
        formData.append("name", document.getElementById("name").value);
        formData.append("product_name", document.getElementById("product_name").value);
        formData.append("link_to_product", document.getElementById("link_to_product").value);
        formData.append("description_product", document.getElementById("description_product").value);

        // เพิ่มไฟล์หน้าปก
        const coverInput = document.getElementById("src_image_cover");
        if (coverInput.files.length > 0) {
            formData.append("src_image_cover", coverInput.files[0]);
        }

        // เพิ่มไฟล์สินค้า
        selectedFiles.forEach((file) => {
            formData.append("src_images[]", file);
        });

        // ส่งข้อมูล
        fetch("save_product.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.text();
            })
            .then((data) => {
                console.log(data);
                alert("Product saved successfully!");
                document.querySelector("form").reset(); // รีเซ็ตฟอร์ม
                preview.innerHTML = ""; // ล้าง preview
                previewCover.innerHTML = ""; // ล้าง preview cover
                selectedFiles.length = 0; // ล้าง selectedFiles
            })
            .catch((error) => {
                console.error("Error:", error);
                showError(`Error occurred: ${error.message}`);
            });
    });
</script>
<style>
    /* Reset Styles */
body, h1, h2, h3, p, a {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
    margin-left: 20px;
    margin-right: 20px;
    margin-top: 50px;

}

body {
    background-color: #f5f5f5;
    color: #333;
    font-size: 16px;
}

/* Container Layout */
.admin_page {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    min-height: 100vh;
    overflow: hidden;
}

.allPage {
    display: flex;
    flex-direction: row;
    gap: 20px;
    margin: 20px;
}

/* Sidebar Menu */
.tabmenuBar {
    width: 250px;
    background-color: #333;
    color: white;
    padding: 20px;
    border-radius: 10px;
}

.tabmenuBar img {
    display: block;
    margin: 0 auto 20px;
    border-radius: 10px;
}

.tabmenuBar .tab {
    background-color: #444;
    margin: 10px 0;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.tabmenuBar .tab:hover {
    background-color: #555;
}

.tabmenuBar .dropdown-menu {
    background-color: #444;
    margin-top: 10px;
    padding: 10px;
    border-radius: 5px;
    display: none;
}

.tabmenuBar .dropdown-item {
    margin: 5px 0;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.tabmenuBar .dropdown-item:hover {
    background-color: #555;
}

/* Main Content Sections */
.ส่วนหน้าเว็บตัวอย่าง, .ส่วนจัดการไฟล์, .ส่วนแสดงผล {
    flex: 1;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.ส่วนหน้าเว็บตัวอย่าง h2, .ส่วนจัดการไฟล์ h2, .ส่วนแสดงผล h2 {
    margin-bottom: 20px;
    color: #444;
}

.ส่วนหน้าเว็บตัวอย่าง a, .ส่วนจัดการไฟล์ a {
    display: block;
    color: #007bff;
    margin: 5px 0;
    text-decoration: none;
    transition: color 0.3s ease;
}

.ส่วนหน้าเว็บตัวอย่าง a:hover, .ส่วนจัดการไฟล์ a:hover {
    color: #0056b3;
}

/* Iframe Styling */
.iframe-content {
    width: 100%;
    height: 400px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Dropdown Animation */
.tab.open + .dropdown-menu {
    display: block;
}

/* Responsive Design */
@media (max-width: 768px) {
    .allPage {
        flex-direction: column;
        gap: 10px;
    }

    .tabmenuBar {
        width: 100%;
    }
}

</style>