/**
 * Detta skript förhindrar att sidan laddas om när ett formulär skickas och istället används AJAX för att skicka
 * data till servern. Efter att produkten lagts till i kundvagnen, visas ett toast-meddelande för att informera
 * användaren om att produkten har lagts till.
 */

/**
 * Funktion för att visa ett toast-meddelande på sidan.
 * Skapar en temporär toast som visas längst ner på skärmen när en produkt läggs till i kundvagnen.
 * Toasten visas i 3 sekunder innan den tas bort.
 */
function showPopup(message) {
  //Skapa en toast-div
  const toast = document.createElement("div");
  toast.classList.add("toast");
  toast.textContent = message;
  
  //Lägg till toasten i body
  document.body.appendChild(toast);

  //Vänta på några sekunder och ta bort toasten
  setTimeout(() => {
    toast.classList.add("show"); //Gör toasten synlig
  }, 100);

  //Ta bort toasten efter 3 sekunder
  setTimeout(() => {
    toast.classList.remove("show"); //Döljer toasten
    document.body.removeChild(toast); //Tar bort toasten från DOM
  }, 3500);
}

/**
 * Hanterar form submission via AJAX för att lägga till produkter i kundvagnen utan att ladda om sidan.
 * Förhindrar att formuläret skickas på vanligt sätt och skickar istället en AJAX-förfrågan till servern.
 * Efter att servern svarat, visar ett toast-meddelande som informerar användaren om att produkten lagts till i kundvagnen.
 */
document.querySelectorAll('.add-cart-form').forEach(function(form) {
  form.addEventListener('submit', function(event) {
    event.preventDefault(); //Förhindrar att formuläret skickas och sidan laddas om
    const formData = new FormData(form);
    
    //Skicka AJAX-förfrågan
    const xhr = new XMLHttpRequest();
    xhr.open("POST", form.action, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        //Visa toast-meddelande efter att produkten har lagts till
        showPopup("Product added to Cart!");
      }
    };
    xhr.send(formData); //Skickar formuläret via AJAX
  });
});
