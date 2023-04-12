$(document).ready(function() {

  $('#result-modals').css('display', 'none');

  //Hover interactions
  $('#add-new-class').hover(
    function(){
      document.getElementById('add-btn1').src = '/TimeDesign/images/add-btn-hover.png';
    }, function(){
      document.getElementById('add-btn1').src = '/TimeDesign/images/add-button.png';
    }
  );

  $('#add-new-job').hover(
    function(){
      document.getElementById('add-btn2').src = '/TimeDesign/images/add-btn-hover.png';
    }, function(){
      document.getElementById('add-btn2').src = '/TimeDesign/images/add-button.png';
    }
  );

  $('#add-new-club').hover(
    function(){
      document.getElementById('add-btn3').src = '/TimeDesign/images/add-btn-hover.png';
    }, function(){
      document.getElementById('add-btn3').src = '/TimeDesign/images/add-button.png';
    });

  $('#help').hover(
    function(){
      document.getElementById('help-btn').src = '/TimeDesign/images/help-btn-hover.png';
    }, function(){
      document.getElementById('help-btn').src = '/TimeDesign/images/help-btn.png';
    }
  );

  //Click handlers
  $('#add-new-class').click(function() {openClassSelect()});

  $('#add-new-job').click(function() {openJobSelect()});

  $('#add-new-club').click(function(){openClubSelect()});

  const key = 'seenModal',
  seenModal = localStorage.getItem(key);
  if (seenModal === null) {
    document.getElementById('new-user-modal').style.display = 'flex';
    document.getElementById('modal-backdrop').style.display = 'block';
    localStorage.setItem(key, true);
  }
});

let selectedCourses = [];
let selectedJobs = [];
let selectedClubs = [];

function waitAnimation() {
  $("#load-animation").css('display', 'block');
  $.get("/TimeDesign/calculate", {
    courses: selectedCourses,
    jobs: selectedJobs,
    clubs: selectedClubs
  }, (res) => {
    const data = JSON.parse(res);
    makeGraphs(data);
    showResults();
  });
}

function updateWeekly() {
  var course = document.getElementById("class_est").textContent;
  var job = document.getElementById("job_est").textContent;
  var club = document.getElementById("ec_est").textContent;
  var min = parseFloat(course.substring(0, course.indexOf('-'))) + parseFloat(job.substring(0, job.indexOf('-'))) + parseFloat(club.substring(0, club.indexOf('-')));
  var max = parseFloat(course.substring(course.indexOf('-')+1)) + parseFloat(job.substring(job.indexOf('-')+1)) + parseFloat(club.substring(club.indexOf('-')+1));
  document.getElementById("weekly_total").textContent = min.toFixed(2) + " - " + max.toFixed(2);
}

function updateInformation(a, b) {
  var std = dev(b);
  var mean = calcMean(b);
  var timeMin = mean - std;
  var timeMax = mean + std;
  document.getElementById(a).textContent = timeMin.toFixed(2) + " - " + timeMax.toFixed(2);
}

function updateClassInformation() {
  var study = document.getElementById("study_est").textContent;
  var lecture = document.getElementById("lecture_est").textContent;
  var hw = document.getElementById("hw_est").textContent;
  var min = parseFloat(study.substring(0, study.indexOf('-'))) + parseFloat(lecture.substring(0, lecture.indexOf('-'))) + parseFloat(hw.substring(0, hw.indexOf('-')));
  var max = parseFloat(study.substring(study.indexOf('-')+1)) + parseFloat(lecture.substring(lecture.indexOf('-')+1)) + parseFloat(hw.substring(hw.indexOf('-')+1));
  document.getElementById("class_est").textContent = min.toFixed(2) + " - " + max.toFixed(2);
}

function calcMean(arr) {
  const len = arr.length;
  if(len == 0) {
    return 0;
  }
  var mean = arr.reduce((ele1, ele2) => ele1 + ele2) / len;
  return mean;
}

function dev(arr){
  if(arr.length == 0) {
    return 0;
  }
  let mean = arr.reduce((acc, curr)=>{
    return acc + curr
  }, 0) / arr.length;
  arr = arr.map((k)=>{
    return (k - mean) ** 2
  })
 let sum = arr.reduce((acc, curr)=> acc + curr, 0);
 return Math.sqrt(sum / arr.length)
}

function grabData(data, typeOfHours, kind) {
  const hours = [];
  if(data.length > 0) {
    for(var a = 0; a < data.length; a++) {
      if(kind == "course") {
        hours.push(parseInt(data[a][typeOfHours])); 
      }
      else if (kind == "job"){
        const toAdd = [];
        toAdd.push(data[a].company);
        toAdd.push(parseInt(data[a][typeOfHours]));
        hours.push(toAdd);
      }
      else if (kind == "club"){
        const toAdd = [];
        toAdd.push(data[a].name);
        toAdd.push(parseInt(data[a][typeOfHours]));
        hours.push(toAdd);
      }
    }
    hours.sort(function(a, b){return a - b});
  }
  return hours;
}

function formatCourseHours(hours) {
  const formatted = [];
  if(hours != 0) {
    var count = 1;
    for(var a = 0; a < hours.length; a++) {
      if(hours[a] == hours[a+1]) {
        count++;
      }
      else {
        formatted.push({x: hours[a], y: count});
        count = 1;
      }
    }
  }
  return formatted;
}

function createJobData(jobHours, id, change) {
  jobHours.push([-100,-100]);
  const data = [];
  const hours = [];
  const blues = ["#00FFFF", "	#004fa3", "#00A2C2", "#00A87E"];
  var totalMin = 0;
  var totalMax = 0;
  for(var i = 0; i < jobHours.length-1; i++) {
    if(jobHours[i][0] == jobHours[i+1][0]) {
      hours.push(jobHours[i][1]);
    }
    else {
      hours.push(jobHours[i][1]);
      var hoursFormatted = formatCourseHours(hours);
      data.push({type: "spline", showInLegend: true, name: jobHours[i][0], color: blues[i%4], dataPoints: hoursFormatted});
      const para = document.createElement("p");
      const para1 = document.createElement("strong");
      var std = dev(hours);
      var mean = calcMean(hours);
      var timeMin = mean - std;
      var timeMax = mean + std;
      totalMin += timeMin;
      totalMax += timeMax;
      const calculated = document.createTextNode(timeMin.toFixed(2) + " - " + timeMax.toFixed(2));
      para1.appendChild(calculated);
      const node1 = document.createTextNode(jobHours[i][0] + ": ");
      const node2 = document.createTextNode(" Hours/Week");
      para.appendChild(node1);
      para.appendChild(para1);
      para.appendChild(node2);
      document.getElementById(id).appendChild(para);
      hours.length = 0;
    }
  }

  document.getElementById(change).textContent = totalMin.toFixed(2) + " - " + totalMax.toFixed(2);
  return data;
}

function makeGraphs(data) {
  //console.log(data);
  //This creates the course graph
  var studyHours = grabData(data.course, 'study_hours', "course");
  var homeworkHours = grabData(data.course, 'homework_hours', "course");
  var lectureHours = grabData(data.course, 'lecture_hours', "course");
  var studyHoursFormatted = formatCourseHours(studyHours);
  var homeworkHoursFormatted = formatCourseHours(homeworkHours);
  var lectureHoursFormatted = formatCourseHours(lectureHours);
  //This updates the course information
  updateInformation('lecture_est', lectureHours);
  updateInformation('study_est', studyHours);
  updateInformation('hw_est', homeworkHours);
  updateClassInformation();

  var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    theme: "light2",
    axisX:{
      title: "Hours",
      includeZero: true,
      crosshair: { enabled: true }
    },
    axisY: {
      minimum: 0,
      title: "Number of Students",
      includeZero: true,
      crosshair: { enabled: true }
    },
    toolTip:{ shared:true },  
    legend:{
      cursor:"pointer",
      verticalAlign: "bottom",
      horizontalAlign: "left",
      dockInsidePlotArea: true,
      itemclick: toogleDataSeries
    },
    data: [{
      type: "spline",
      showInLegend: true,
      name: "Study",
      color: "#00A87E",
      dataPoints: studyHoursFormatted
    },
    {
      type: "spline",
      showInLegend: true,
      name: "Homework",
      color: "#00A2C2",
      dataPoints: homeworkHoursFormatted
    },
    {
      type: "spline",
      showInLegend: true,
      name: "Lecture",
      color: "#004fa3",
      dataPoints: lectureHoursFormatted
    }]
  });
  chart.render();

  //This creates the job graph
  document.getElementById("job_est").textContent = "";
  var jobHours = grabData(data.job, 'hours', "job");
  var jobData = createJobData(jobHours, "job_p", "job_est");
  var chart1 = new CanvasJS.Chart("chartContainer1", {
    animationEnabled: true,
    theme: "light2",
    axisX:{
      title: "Hours",
      includeZero: true,
      crosshair: { enabled: true }
    },
    axisY: {
      minimum: 0,
      title: "Number of Students",
      includeZero: true,
      crosshair: { enabled: true }
    },
    toolTip:{ shared:true },  
    legend:{
      cursor:"pointer",
      verticalAlign: "bottom",
      horizontalAlign: "left",
      dockInsidePlotArea: true,
      itemclick: toogleDataSeries
    },
    data: jobData
  });
  chart1.render();

  //This creates the extracurricular graph
  document.getElementById("ec_est").textContent = "";
  var clubHours = grabData(data.club, 'hours', "club");
  var clubData = createJobData(clubHours, "ec_p", "ec_est");
  var chart2 = new CanvasJS.Chart("chartContainer2", {
    animationEnabled: true,
    theme: "light2",
    axisX:{
      title: "Hours",
      includeZero: true,
      crosshair: { enabled: true }
    },
    axisY: {
      minimum: 0,
      title: "Number of Students",
      includeZero: true,
      crosshair: { enabled: true }
    },
    toolTip:{ shared:true },  
    legend:{
      cursor:"pointer",
      verticalAlign: "bottom",
      horizontalAlign: "left",
      dockInsidePlotArea: true,
      itemclick: toogleDataSeries
    },
    data: clubData
  });
  chart2.render();

  updateWeekly();

  function toogleDataSeries(e){
    if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
      e.dataSeries.visible = false;
    } else{
      e.dataSeries.visible = true;
    }
    chart.render();
  }
}

function showResults() {
  $("#load-animation").css('display', 'none');
  $("#result-modals").css('display', 'flex');
}

function openClassSelect() {
  document.getElementById('modal-backdrop').style.display = 'block';
  document.getElementById('courses-modal').style.display = 'flex';
  document.getElementById('courses-modal').style.animationName = 'popup';
}

function openJobSelect() {
  document.getElementById('modal-backdrop').style.display = 'block';
  document.getElementById('jobs-modal').style.display = 'flex';
  document.getElementById('jobs-modal').style.animationName = 'popup';
}

function openClubSelect() {
  document.getElementById('modal-backdrop').style.display = 'block';
  document.getElementById('clubs-modal').style.display = 'flex';
  document.getElementById('clubs-modal').style.animationName = 'popup';
}

function closeModal(modal) {
  document.getElementById('modal-backdrop').style.display = 'none';
  if (modal == 'class') {
    document.getElementById('courses-modal').style.display = 'none';
  } else if (modal == 'job') {
    document.getElementById('jobs-modal').style.display = 'none';
  } else if (modal == 'club') {
    document.getElementById('clubs-modal').style.display = 'none';
  } else if (modal == 'tutorial') {
    document.getElementById('new-user-modal').style.display = 'none';
  }
}

function searchClass() {
  const val = $('#cl-srch').val();
  $.get("/TimeDesign/courses", { name: val }, (res) => {
    const data = JSON.parse(res);
    const divEl = document.getElementById("cl-res");
    divEl.innerHTML = "";
    for (const course of data) {
      const bt = document.createElement("button");
      bt.textContent = course.school_course_id + " " + course.name;
      bt.setAttribute('data-id', course.id);
      bt.onclick = selectClass;
      divEl.appendChild(bt);
    }
  });
}

function selectClass(e) {
  const id = e.target.dataset.id;
  if (selectedCourses.filter(c => c.id === id).length === 0) {
    selectedCourses.push({ id: id, name: e.target.textContent });
  }
  closeModal('class');
  const divEl = document.getElementById("cl-res");
  divEl.innerHTML = "";
  renderItems();
}

function renderItems() {
  const types = [
    {
      type: "class",
      emptyString: "No classes added yet!"
    },
    {
      type: "job",
      emptyString: "No jobs added yet!"
    },
    {
      type: "club",
      emptyString: "No activities added yet!"
    }
  ];
  for (const type of types) {
    const list = document.getElementById(`${type.type}-items`);
    list.innerHTML = "";
    let theList;
    switch (type.type) {
      case "class":
        theList = selectedCourses;
        break;
      case "job":
        theList = selectedJobs;
        break;
      case "club":
        theList = selectedClubs;
    }
    if (theList.length === 0) {
      const li = document.createElement("li");
      li.classList.add("item");
      li.classList.add("empty");
      li.textContent = type.emptyString;
      list.appendChild(li);
    }
    else {
      for (const item of theList) {
        const li = document.createElement("li");
        li.classList.add("item");
        li.textContent = item.name;
        li.setAttribute("data-id", item.id);
        if (type.type === "class") {
          li.onclick = deleteClass;
        }
        else if (type.type === "job") {
          li.onclick = deleteJob;
        }
        else if (type.type === "club") {
          li.onclick = deleteClub;
        }
        list.appendChild(li);
      }
    }
  }
}

function searchJobs() {
  const val = $('#jb-srch').val();
  $.get("/TimeDesign/jobs", { name: val }, (res) => {
    const data = JSON.parse(res);
    const divEl = document.getElementById("jb-res");
    divEl.innerHTML = "";
    for (const job of data) {
      const bt = document.createElement("button");
      bt.textContent = job.company + " | " + job.name;
      bt.setAttribute('data-id', job.id);
      bt.onclick = selectJob;
      divEl.appendChild(bt);
    }
  });
}

function selectJob(e) {
  const id = e.target.dataset.id;
  if (selectedJobs.filter(c => c.id === id).length === 0) {
    selectedJobs.push({ id: id, name: e.target.textContent });
  }
  closeModal('job');
  const divEl = document.getElementById("jb-res");
  divEl.innerHTML = "";
  renderItems();
}

function searchClubs() {
  const val = $('#ec-srch').val();
  $.get("/TimeDesign/clubs", { name: val }, (res) => {
    const data = JSON.parse(res);
    const divEl = document.getElementById("ec-res");
    divEl.innerHTML = "";
    for (const club of data) {
      const bt = document.createElement("button");
      bt.textContent = club.name;
      bt.setAttribute('data-id', club.id);
      bt.onclick = selectClub;
      divEl.appendChild(bt);
    }
  });
}

function selectClub(e) {
  const id = e.target.dataset.id;
  if (selectedClubs.filter(c => c.id === id).length === 0) {
    selectedClubs.push({ id: id, name: e.target.textContent });
  }
  closeModal('club');
  const divEl = document.getElementById("ec-res");
  divEl.innerHTML = "";
  renderItems();
}

function deleteClass(e) {
  const id = e.target.dataset.id;
  selectedCourses = selectedCourses.filter(c => c.id !== id);
  renderItems();
}

function deleteJob(e) {
  const id = e.target.dataset.id;
  selectedJobs = selectedJobs.filter(c => c.id !== id);
  renderItems();
}

function deleteClub(e) {
  const id = e.target.dataset.id;
  selectedClubs = selectedClubs.filter(c => c.id !== id);
  renderItems();
}

function showTutorial() {
  document.getElementById('new-user-modal').style.display = 'flex';
  document.getElementById('modal-backdrop').style.display = 'block';
}