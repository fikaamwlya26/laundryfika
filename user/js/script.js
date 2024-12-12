const prevIcon = '<div class="prev-slide"><i class="fa fa-solid fa-chevron-left fa-lg"></i></div>';
const nextIcon = '<div class="next-slide"><i class="fa fa-solid fa-chevron-right fa-lg"></i></div>';

$('.owl-carousel').owlCarousel({
    center: true,
    loop: true,
    margin: 15,
    nav: true,
    navText: [
        prevIcon,
        nextIcon
    ],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 2.3
            // items: 1.75 /* Optional */
        }
    }
})


$(document).ready(function(){
    $(".owl-carousel").owlCarousel({
      loop: true,
      margin: 10,
      nav: true, // Tombol navigasi
      responsive:{
          0:{
              items:1 // Menampilkan 1 item per layar kecil
          },
          600:{
              items:3 // Menampilkan 3 item per layar medium
          },
          1000:{
              items:5 // Menampilkan 5 item per layar besar
          }
      }
    });
  });
  
 
  document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Button clicked: ' + this.textContent);
        });
    });
});

// Ambil elemen-elemen yang dibutuhkan
const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');
const testimonialCards = document.querySelector('.testimonial-cards');
const cardWidth = document.querySelector('.testimonial-card').offsetWidth + 20;  // Width card + margin

let currentIndex = 0;  // Untuk menyimpan posisi saat ini

// Fungsi untuk menyesuaikan posisi kartu saat tombol prev atau next diklik
function moveSlider() {
    testimonialCards.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
}

// Menangani tombol next (geser ke kanan)
nextBtn.addEventListener('click', () => {
    if (currentIndex < testimonialCards.children.length - 1) {
        currentIndex++;
        moveSlider();
    }
});

// Menangani tombol prev (geser ke kiri)
prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        moveSlider();
    }
});

  
  