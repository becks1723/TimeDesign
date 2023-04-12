$( document ).ready(function() {
  document.getElementById("coursesbutton").click();
});


function courseSubmit() {
  var i = true;
  var y, allInput;
  allInput = document.forms["courseForm"].getElementsByClassName("form-control");
  for(y = 0; y < allInput.length-1; y++) {
    if(allInput[y].value == "") {
      i = false;
      allInput[y].style.border = "solid 2px red";
      allInput[y].placeholder = "Required";
    }
    if(allInput[y].value !== "") {
      allInput[y].style.border = "solid 1px #ced4da";
    }
  }
  if(i) {
    window.alert("Your review has been submitted! Thank you.");
  }
}

function jobSubmit() {
  var i = true;
  var y, allInput, allRadio;
  allInput = document.forms["jobForm"].getElementsByClassName("form-control");
  for(y = 0; y < allInput.length; y++) {
    if(y == 0 && allInput[y].value == "Choose Here") {
      i = false;
      allInput[y].style.border = "solid 2px red";
    }
    if(y == 0 && allInput[y].value != "Choose Here") {
      allInput[y].style.border = "solid 1px #ced4da";
    }
    if( y != 0){
      if(allInput[y].value == "") {
        i = false;
        allInput[y].style.border = "solid 2px red";
        allInput[y].placeholder = "Required";
      }
      if(allInput[y].value != "") {
        allInput[y].style.border = "solid 1px #ced4da";
      }
    }
  }
  var checked = false;
  allRadio = document.forms["jobForm"].getElementsByClassName("form-check-input");
  for(y = 0; y < allRadio.length; y++) {
    if(allRadio[y].checked) {
      checked = true;
      document.getElementById("radio-check-small").textContent = "";
    }
  }
  if(!checked) {
    i = false;
    document.getElementById("radio-check-small").textContent = "Please select an option";
  }
  if(i) {
    window.alert("Your review has been submitted! Thank you.");
  }
}

function ECSubmit() {
  var i = true;
  var y, allInput;
  allInput = document.forms["ECForm"].getElementsByClassName("form-control");
  for(y = 0; y < allInput.length; y++) {
    if(y == 0 && allInput[y].value == "Choose Here") {
      i = false;
      allInput[y].style.border = "solid 2px red";
    }
    if(y == 0 && allInput[y].value != "Choose Here") {
      allInput[y].style.border = "solid 1px #ced4da";
    }
    if( y != 0) {
      if(allInput[y].value == "") {
        i = false;
        allInput[y].style.border = "solid 2px red";
        allInput[y].placeholder = "Required";
      }
      if(allInput[y].value != "") {
        allInput[y].style.border = "solid 1px #ced4da";
      }
    }
  }
  if(i) {
    window.alert("Your review has been submitted! Thank you.");
  }
}


function openSection(evt, sectionName) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(sectionName).style.display = "block";
  evt.currentTarget.className += " active";
}

function showModal() {
  let modal = document.getElementById("myModal");
  modal.style.display = "block";
  $(".btn-primary").css('z-index', 0);
}

function closeModal() {
  let modal = document.getElementById("myModal");
  modal.style.display = "none";
  $(".btn-primary").css('z-index', 2);
}


function submitted() {
  const type = document.forms["modal-form"]["flexRadioDefault"].value;
  let name = document.forms["modal-form"]["name"].value;
  if(name === "") {
    document.forms["modal-form"]["name"].style.border = "solid 2px red";
    document.forms["modal-form"]["name"].placeholder = "Required";
    return;
  }
  else {
    document.forms["modal-form"]["name"].style.border = "solid 1px #ced4da";
  }
  let courseId;
  if (type === "course") {
    courseId = document.forms["modal-form"]["courseId"].value;
    if(courseId === "") {
      document.forms["modal-form"]["courseId"].style.border = "solid 2px red";
      document.forms["modal-form"]["courseId"].placeholder = "Required";
      return;
    }
    else {
      document.forms["modal-form"]["courseId"].style.border = "solid 1px #ced4da";
    }
  }
  let companyName;
  if (type === "job") {
    companyName = document.forms["modal-form"]["companyName"].value;
    if(companyName === "") {
      document.forms["modal-form"]["companyName"].style.border = "solid 2px red";
      document.forms["modal-form"]["companyName"].placeholder = "Required";
      return;
    }
    else {
      document.forms["modal-form"]["companyName"].style.border = "solid 1px #ced4da";
    }
  }
  let checked = false;
  let allRadio = document.forms["modal-form"].getElementsByClassName("form-check-input");
  for(let y = 0; y < allRadio.length; y++) {
    if(allRadio[y].checked) {
      checked = true;
      document.getElementById("radio-check-small1").textContent = "";
    }
  }
  if(!checked) {
    document.getElementById("radio-check-small1").textContent = "Please select an option";
    return;
  }
  if (type === "course") {
    $.post("/TimeDesign/course", { name: name, courseId: courseId, xsrf_token: xsrf }, (res) => {
      const data = JSON.parse(res);
      if (data['status'] === 'success') {
        alert('Submitted!');
        closeModal();
        return;
      }
      alert('Failed to submit. Please make sure all fields are filled in.');
    });
  }
  else if (type === "job") {
    $.post("/TimeDesign/job", { name: name, companyName: companyName, xsrf_token: xsrf }, (res) => {
      const data = JSON.parse(res);
      if (data['status'] === 'success') {
        alert('Submitted!');
        closeModal();
        return;
      }
      alert('Failed to submit. Please make sure all fields are filled in.');
    });
  }
  else {
    $.post("/TimeDesign/club", { name: name, xsrf_token: xsrf }, (res) => {
      const data = JSON.parse(res);
      if (data['status'] === 'success') {
        alert('Submitted!');
        closeModal();
        return;
      }
      alert('Failed to submit. Please make sure all fields are filled in.');
    });
  }
}

function courseModal(){
  $("#course-id-wrapper").show();
  $("#company-name-wrapper").hide();
  document.getElementById("modal-select").textContent = "course";
}

function jobModal(){
  $("#course-id-wrapper").hide();
  $("#company-name-wrapper").show();
  document.getElementById("modal-select").textContent = "job";
}

function ecModal(){
  $("#course-id-wrapper").hide();
  $("#company-name-wrapper").hide();
  document.getElementById("modal-select").textContent = "extracurricular";
}
