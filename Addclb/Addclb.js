// Biến để lưu trữ danh sách các MSV đã chọn
var selectedMSVs = [];
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
    displaySelectedMSVs(); // Hiển thị lại danh sách đã chọn
    updateMemberCount(); // Cập nhật số lượng thành viên
}

// Function để cập nhật số lượng thành viên
function updateMemberCount() {
    var memberCountInput = document.getElementById('memberCount');
    memberCountInput.value = selectedMSVs.length; // Cập nhật số lượng thành viên với độ dài của mảng selectedMSVs
}



function displaySelectedMSVs() {
    var selectedMSVsElement = document.getElementById('selectedMSVs');
    if (!selectedMSVsElement) return; // Kiểm tra xem phần tử 'selectedMSVs' có tồn tại không

    selectedMSVsElement.innerHTML = ''; // Xóa nội dung hiện tại của danh sách

    // Loop qua danh sách các MSV đã chọn và hiển thị chúng
    for (var i = 0; i < selectedMSVs.length; i++) {
        var listItem = document.createElement('li');
        listItem.textContent = selectedMSVs[i];
        
        // Thêm nút để xóa MSV khỏi danh sách khi được nhấp
        var deleteButton = document.createElement('button');
        deleteButton.textContent = 'Xóa';
        deleteButton.setAttribute('onclick', 'removeSelectedMSV(' + i + ')');
        listItem.appendChild(deleteButton);

        selectedMSVsElement.appendChild(listItem);
    }

    // Cập nhật số lượng MSV đã chọn
    var countInput = document.getElementById('count');
    if (countInput) {
        countInput.value = selectedMSVs.length;
    }
}

