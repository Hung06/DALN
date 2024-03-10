// Biến để lưu trữ danh sách các MSV đã chọn
var selectedMSVs = [];

// Function để thêm MSV vào danh sách đã chọn
function addSelectedOption() {
    var input = document.getElementById('MSVInput');
    var inputValue = input.value.trim().toUpperCase(); // Chuyển đổi thành chữ hoa để không phân biệt hoa thường

    // Kiểm tra xem MSV đã được chọn trước đó chưa và không trùng lặp
    if (inputValue && !selectedMSVs.includes(inputValue)) {
        selectedMSVs.push(inputValue); // Thêm MSV vào danh sách đã chọn
        displaySelectedMSVs(); // Hiển thị danh sách đã chọn
        input.value = ''; // Xóa giá trị đầu vào
        updateMemberCount(); // Cập nhật số lượng thành viên
    }
}

// Function để xóa MSV khỏi danh sách đã chọn
function removeSelectedMSV(index) {
    selectedMSVs.splice(index, 1); // Xóa phần tử tại vị trí chỉ định từ mảng
    updateMemberCount(); // Cập nhật lại số lượng thành viên
    displaySelectedMSVs(); // Hiển thị lại danh sách đã chọn
}

// Function để cập nhật số lượng thành viên
function updateMemberCount() {
    var memberCountInput = document.getElementById('count');
    memberCountInput.value = selectedMSVs.length; // Cập nhật số lượng thành viên với độ dài của mảng selectedMSVs
}

// Function để hiển thị danh sách các MSV đã chọn
function displaySelectedMSVs() {
    var selectedMSVsContainer = document.getElementById('selectedMSVsContainer'); // Lấy vùng chứa danh sách MSV
    if (!selectedMSVsContainer) return;

    // Xóa nội dung hiện tại của vùng chứa danh sách MSV
    selectedMSVsContainer.innerHTML = '';

    // Loop qua danh sách các MSV đã chọn và tạo một phần tử div cho mỗi MSV
    for (var i = 0; i < selectedMSVs.length; i++) {
        var MSVDiv = document.createElement('div');
        MSVDiv.textContent = selectedMSVs[i];

        // Tạo nút "Xóa" cho mỗi MSV và gán sự kiện click để xóa MSV khi được nhấp
        var deleteButton = document.createElement('button');
        deleteButton.textContent = 'Xóa';
        deleteButton.setAttribute('data-index', i); // Lưu chỉ số của MSV để xác định MSV cần xóa
        deleteButton.addEventListener('click', function() {
            var index = parseInt(this.getAttribute('data-index')); // Lấy chỉ số của MSV cần xóa
            removeSelectedMSV(index); // Gọi hàm removeSelectedMSV để xóa MSV
        });

        // Thêm nút "Xóa" vào div của MSV
        MSVDiv.appendChild(deleteButton);

        // Thêm div của MSV vào vùng chứa danh sách MSV
        selectedMSVsContainer.appendChild(MSVDiv);
    }

    // Cập nhật giá trị trường ẩn selectedMSVsInput
    var selectedMSVsInput = document.getElementById('selectedMSVsInput');
    selectedMSVsInput.value = selectedMSVs.join(', ');
}
