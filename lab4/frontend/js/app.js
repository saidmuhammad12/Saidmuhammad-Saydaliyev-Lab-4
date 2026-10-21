// Frontend JavaScript: talks to the PHP backend
const API = "../backend";
// Hide the logo box if the image file has not been added yet
document.querySelectorAll(".logo").forEach(img => {
img.addEventListener("error", () => { img.style.display = "none"; });
});
// ---------- Home page: check backend ----------
const status = document.getElementById("server-status");
if (status) {
fetch(API + "/get_students.php")
.then(r => r.json())
.then(d => { status.textContent = "Backend connected ✔ (" + d.count + " students saved)";
status.className = "ok"; })
.catch(() => { status.textContent = "Backend not reachable – run with a PHP server (see README)"; status.className = "bad"; });
}
// ---------- Form page: send data to backend ----------
const form = document.getElementById("studentForm");
if (form) {
form.addEventListener("submit", e => {
e.preventDefault();
const msg = document.getElementById("msg");
fetch(API + "/submit.php", { method: "POST", body: new FormData(form) })
.then(r => r.json())
.then(d => {
if (d.success) {
msg.className = "success";
msg.innerHTML = "<b>" + d.message + "</b> <a href='table.html'>View table →</a>";
form.reset();
} else {
msg.className = "error";
msg.innerHTML = d.errors.map(x => "<p>• " + x + "</p>").join("");
}
})
.catch(() => {
msg.className = "error";
msg.textContent = "Could not reach the backend. Start a PHP server (see README).";
});
});
}
// ---------- Table page: load data from backend ----------
const body = document.getElementById("tableBody");
if (body) {
fetch(API + "/get_students.php")
.then(r => r.json())
.then(d => {
if (d.students.length === 0) {
body.innerHTML = "<tr><td colspan='7'>No students yet.</td></tr>";
return;
}
body.innerHTML = d.students.map((s, i) => `
<tr>
<td>${i + 1}</td>
<td>${s.enrollment}</td>
<td>${s.name}</td>
<td>${s.course}</td>
<td>${s.gender}</td>
<td>${s.marks}</td>
<td>${s.grade}</td>
</tr>`).join("");
document.getElementById("average").textContent = d.average;
})
.catch(() => {
body.innerHTML = "<tr><td colspan='7'>Could not load data. Start a PHP server (see README).</td></tr>";
});
}