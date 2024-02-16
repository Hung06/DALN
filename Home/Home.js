    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.acc-dow').addEventListener('click', function() {
            var userDrop = document.querySelector('.user-drop');
            userDrop.style.display = (userDrop.style.display === 'block') ? 'none' : 'block';
        });
    });
    const itemsPerPage = 10; // Số lượng phần tử trên mỗi trang
    let currentPage = 1; // Trang hiện tại
    
    function generateData() {
      // Tạo mảng dữ liệu giả định
      const CLBData = Array.from({ length: 50 }, (_, index) => ({
        name: `CLB${index + 1}`,
        image: `/img/avt${index + 1}.jpg`,
      }));
    
      return CLBData;
    }
    
    function displayCLBData() {
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;
      const currentCLBData = CLBData.slice(startIndex, endIndex);
  
      const contentBottom = document.querySelector('.content-bottom');
      
      updatePagination();
  }
  
    
    function createCLBItem(clb) {
      return `
        <div class="CLB-item">
          <div class="CLB-itme-img">
            <img src="${clb.image}" alt="">
          </div>
          <span>${clb.name}</span>
        </div>
      `;
    }
    
    function updatePagination() {
      const totalPages = Math.ceil(CLBData.length / itemsPerPage);
      const paginationContainer = document.getElementById('pagination-container');
    
      highlightCurrentPageButton();
    }
    
    function generatePaginationButtons(totalPages) {
      let buttonsHtml = '';
    
      for (let i = 1; i <= totalPages; i++) {
        buttonsHtml += `<button onclick="changePage(${i})">${i}</button>`;
      }
    
      return buttonsHtml;
    }
    
    function changePage(pageNumber) {
      currentPage = pageNumber;
      displayCLBData();
    }
    
    function highlightCurrentPageButton() {
      const buttons = document.querySelectorAll('#pagination-container button');
      buttons.forEach((button, index) => {
        button.classList.toggle('active', index + 1 === currentPage);
      });
    }
    
    // Gọi hàm hiển thị dữ liệu khi trang được tải
    const CLBData = generateData();
    displayCLBData();
    
      
    function highlightCurrentPageButton() {
      const buttons = document.querySelectorAll('#pagination-container button');
      buttons.forEach((button, index) => {
        button.classList.toggle('active', index + 1 === currentPage);
      });
    }
      
      // Gọi hàm hiển thị dữ liệu khi trang được tải
    displayCLBData();
    


      // Timf kieems
    document.addEventListener('DOMContentLoaded', function() {
      const searchForm = document.getElementById('searchForm');
      const searchInput = document.getElementById('searchInput');
      const searchIcon = document.getElementById('searchIcon');
  
      // Sự kiện nghe khi form được submit
      searchForm.addEventListener('submit', function(event) {
          event.preventDefault(); // Ngăn chặn việc submit form
          searchCLBs();
      });
  
      // Sự kiện nghe khi icon tìm kiếm được click
      searchIcon.addEventListener('click', function() {
          searchCLBs();
      });
  
      // Hàm thực hiện tìm kiếm CLBs
      function searchCLBs() {
          const searchTerm = searchInput.value.trim();
  
          // Nếu từ khóa tìm kiếm không rỗng
          if (searchTerm !== '') {
              // Redirect đến trang search.php với từ khóa tìm kiếm
              window.location.href = `Search.php?searchTerm=${encodeURIComponent(searchTerm)}`;
          }
      }
  });
  
  
  
    
    
    