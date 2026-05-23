function dataActual() {
  let span = document.querySelector('.dataActual')
  let data = new Date()
  dia = data.getDate()
  diaSemanal = data.getDay() + 1
  mes = data.getMonth() + 1
  ano = data.getFullYear()
  hora = data.getHours()
  min = data.getMinutes()
  seg = data.getSeconds()
  switch (diaSemanal) {
    case 1:
      diaSemanal = "Domingo,";
      break;
    case 2:
      diaSemanal = "Segunda F,";

      break;
    case 3:
      diaSemanal = "Terça F,";
      break;
    case 4:
      diaSemanal = "Quarta F,";
      break;
    case 5:
      diaSemanal = "Quinta F,";
      break;
    case 6:
      diaSemanal = "Sexta F,";
      break;
    case 7:
      diaSemanal = "Sábado,";

      break;

    default:
      diaSemanal = "";
      break;
  }
  span.innerHTML = `${diaSemanal} Aos ${dia}/${mes}/${ano} ${hora}:${min}:${seg}`
}
setInterval(() => {
  dataActual()
}, 111);