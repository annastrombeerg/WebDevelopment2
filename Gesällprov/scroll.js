/**
 * Detta script använder IntersectionObserver för att animera en <section>-tagg 
 * när den blir synlig i viewport. Klassen 'in-view' läggs till när sektionen 
 * hamnar inom 10% av synfältet.
 */

document.addEventListener("DOMContentLoaded", function () {
  //Hämta section-elementet från dokumentet
  const section = document.querySelector("section");
  //Skapa en observer som reagerar när elementet blir synligt
  const observer = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach(entry => {
        //Om sektionen är synlig, lägg till klass och sluta observera
        if (entry.isIntersecting) {
          entry.target.classList.add("in-view");
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1, //Minst 10% av elementet måste vara synligt
    }
  );
  //Observera sektion om den finns på sidan
  if (section) {
    observer.observe(section);
  }
});
