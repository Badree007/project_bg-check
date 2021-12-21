admin();
showPage();
login_signup();
saveDetails();
imagePreview();

function admin() {
  const modal = `<div id='myModal' class='modal'>
                  <span class='close'>&times;</span>
                  <img class='image'>
                </div>`;

  document.querySelector("body").insertAdjacentHTML("afterbegin", modal);
  const apps = document.querySelectorAll(".application");

  if (!apps) return;
  for (const app of apps) {
    const id = app.id;

    app.addEventListener("click", function (e) {
      const elm = e.target;
      // console.log(elm, id);
      if (elm.classList.contains("action_btn")) {
        if (elm.classList.contains("btn-aprv")) {
          console.log("approve");
          act("approve", id);
        } else if (elm.classList.contains("btn-rej")) {
          console.log("reject");
          act("reject", id);
        } else if (elm.classList.contains("download_btn")) {
          // console.log("Download");
          window.open(`pdf.php?id=${id}`);
        } else if (elm.classList.contains("feedback_btn")) {
          feedbackModal(id);
        }
      } else if (elm.classList.contains("photoid")) {
        imagePreview(elm);
      }
    });
  }
}

function act(action, id) {
  const data = {
    actor: "admin",
    id: id,
    do: action,
  };
  fetch("includes/admin.inc.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);
      location.reload();
    })
    .catch((error) => {
      console.log("Error:", error);
    });
}

function imagePreview(image) {
  if (!image) return;
  const modal = document.querySelector(".modal");
  const modalImage = document.querySelector(".image");
  const span = document.querySelector(".close");

  modal.style.display = "grid";
  modalImage.src = image.src;

  span.addEventListener("click", function () {
    modal.style.display = "none";
  });
}

function feedbackModal(id) {
  const div = document.querySelector(".feedback_modal");
  const closeBtn = document.querySelector(".fb_close");
  div.style.display = "flex";

  submitFeedback(div, id);
  closeBtn.addEventListener("click", function () {
    div.style.display = "none";
  });
}

function submitFeedback(container, id) {
  const feedForm = document.querySelector(".feedback_form");

  if (!feedForm) return;

  feedForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const text = feedForm.elements.feedback_text.value;
    // console.log(text);
    // console.log(id);
    $data = {
      appID: id,
      feedText: text,
    };
    fetch("includes/leaveFeedback.inc.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify($data),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Success:", data);
        container.style.display = "none";
        feedForm.reset();
        location.reload();
      })
      .catch((error) => {
        console.log("Error:", error);
      });
  });
}

function login_signup() {
  const pages = document.querySelectorAll(".page");
  const switch_page = document.querySelectorAll(".switch-page");

  for (const btn of switch_page) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      for (const page of pages) {
        page.classList.contains("current")
          ? page.classList.remove("current")
          : page.classList.add("current");
      }
    });
  }

  signup();
}

function signup() {
  const signupBtn = document.getElementById("signup-submit");
  const form = document.querySelector(".form-signup");
  const errorField = document.querySelector(".signuperror");

  const mailformat =
    /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/;

  if (!signupBtn) return;
  signupBtn.addEventListener("click", function (e) {
    e.preventDefault();
    const array = Array.from(form.getElementsByTagName("input"));

    let error = "";

    if (array.some((input) => input.value === "")) {
      console.log("Empty fields");
      error = "Fill in all fields!";
    } else if (form.uid.value.toLowerCase() === "admin") {
      console.log("Cannot take admin as username.");
      error = "Cannot take admin as username.";
    } else if (!form.mail.value.match(mailformat)) {
      console.log("Invalid Email!");
      error = "Invalid Email!";
    } else if (form.pwd.value.length < 5) {
      console.log("Password must be at least 5 character long");
      error = "Password must be at least 5 characters long";
    } else if (form.pwd.value !== form.pwd_repeat.value) {
      console.log("Passwords donot match!");
      error = "Passwords donot match!";
    } else {
      const signupdata = {
        uid: form.uid.value,
        mail: form.mail.value,
        pwd: form.pwd.value,
      };

      fetch("includes/signup.inc.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(signupdata),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Success:", data);
          if (data.err) {
            if (data.err === "username taken") {
              console.log(data.err);
              error =
                "User already registered. Use different username and email.";
            } else {
              error = "Data saved failed. Contact Admin!";
            }
          } else {
            error = `Account created with username ${data.username}`;
            errorField.style.color = "green";
            form.reset();
          }

          errorField.textContent = error;
        })
        .catch((error) => {
          console.log("Error:", error);
        });
    }
    errorField.textContent = error;
  });
}

function showPage() {
  //   const viewAppBtn = document.querySelector(".app_view-btn");
  //   const applyAppBtn = document.querySelector(".apply-btn");
  const btns = document.querySelectorAll(".page_show");
  const pages = document.querySelectorAll(".rel_page");

  for (const btn of btns) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();

      for (const page of pages) {
        if (page.classList.contains("active")) page.classList.remove("active");

        if (e.target.dataset.num == page.dataset.num)
          page.classList.add("active");
      }
    });
  }
}

function saveDetails() {
  const stNo = document.getElementById("stno");
  const street = document.getElementById("stname");
  const city = document.getElementById("city");
  const postcode = document.getElementById("postal");
  const state = document.getElementById("state");
  const errorField = document.querySelector(".error");
  const formCheck = document.querySelector(".form-checkAdd");

  if (!formCheck) return;
  formCheck.addEventListener("submit", function (e) {
    e.preventDefault();

    const cityVal = city.value;
    const postcodeVal = postcode.value;
    let verify = false;
    const formData = new FormData(formCheck);

    verifyAddress(postcodeVal).then((data) => {
      console.log(data);
      if (data) {
        for (const val of data.cities) {
          if (val == cityVal) {
            console.log("Match");
            verify = true;
          } else {
            console.log("No match");
          }
        }
      } else {
        console.log("Postcode not found!!!");
      }
    }).then( function() {
      if (verify) {
        formData.append("addressVerify", "success");
      } else {
        formData.append("addressVerify", "fail");
      }

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "includes/save-details.inc.php", true);

      xhr.addEventListener("load", function () {
        if (xhr.status === 200) {
          console.log("Data saved");
          formCheck.reset();
          errorField.textContent = "Data saved. Thanks for applying!";
          errorField.style.color = "green";
        } else if (xhr.status !== 200) {
          console.log("Request failed.  Returned status of " + xhr.status);
          errorField.textContent = `Data failed to be saved. ${xhr.status}, Contact Admin.`;
          errorField.style.color = "red";
        }
      });

      xhr.send(formData);
    }

    );

    // setTimeout(function () {
    //   if (verify) {
    //     formData.append("addressVerify", "success");
    //   } else {
    //     formData.append("addressVerify", "fail");
    //   }

    //   let xhr = new XMLHttpRequest();
    //   xhr.open("POST", "includes/save-details.inc.php", true);

    //   xhr.addEventListener("load", function () {
    //     if (xhr.status === 200) {
    //       console.log("Data saved");
    //       formCheck.reset();
    //       errorField.textContent = "Data saved. Thanks for applying!";
    //       errorField.style.color = "green";
    //     } else if (xhr.status !== 200) {
    //       console.log("Request failed.  Returned status of " + xhr.status);
    //       errorField.textContent = `Data failed to be saved. ${xhr.status}, Contact Admin.`;
    //       errorField.style.color = "red";
    //     }
    //   });

    //   xhr.send(formData);
    // }, 1000);
  });
}

function verifyAddress(postcode) {
  return fetch(`https://api-postcode.herokuapp.com/address_api.json/?pcode=${postcode}`, {
    method: 'GET',
    mode: 'cors',
    credentials: 'include',
    headers: {
      "Content-type": "application/json",
    }
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      return data;
    })
    .catch((error) => {
      console.log("Error:", error);
    });
}

/* 
1. cd C:\Program Files (x86)\Google\Chrome\Application  
2. chrome.exe --user-data-dir="C:/Chrome dev session" --disable-web-security
**/
