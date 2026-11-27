/**
 * Minimal UI-Only Script (js/ui.js)
 * Handles visual interactions only. All data/business logic lives in PHP.
 */
document.addEventListener("DOMContentLoaded", () => {
  // 1. Mobile Sidebar Toggle
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  if (menuToggle && sidebar) {
    menuToggle.addEventListener("click", () => sidebar.classList.toggle("open"));
  }

  // 2. Notification Dropdown
  const notifBtn = document.getElementById("notifBtn");
  const notifDropdown = document.getElementById("notifDropdown");
  if (notifBtn && notifDropdown) {
    notifBtn.addEventListener("click", () => {
      const isHidden = notifDropdown.classList.toggle("hidden");
      notifBtn.setAttribute("aria-expanded", String(!isHidden));
    });
  }

  // 3. Login Quick-Fill Dummy Buttons
  document.querySelectorAll("[data-fill-login]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const idInput = document.getElementById("user_id_input");
      const passInput = document.getElementById("password");
      if (idInput) idInput.value = btn.dataset.id;
      if (passInput) passInput.value = btn.dataset.pass;
    });
  });

  // 4. Copy Meeting Link
  document.querySelectorAll("[data-copy-link]").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (navigator.clipboard) navigator.clipboard.writeText(btn.dataset.copyLink);
      const container = document.getElementById("toastContainer");
      if (container) {
        const toast = document.createElement("div");
        toast.className = "toast success";
        toast.textContent = "Meeting link copied to clipboard.";
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
      }
    });
  });

  // 5. Student: Open Booking Modal
  const bookingModal = document.getElementById("bookingModal");
  if (bookingModal) {
    document.querySelectorAll("[data-open-booking]").forEach((btn) => {
      btn.addEventListener("click", () => {
        document.getElementById("modalSlotId").value = btn.dataset.slotId;
        document.getElementById("course_code").value = btn.dataset.course;
        document.getElementById("bookingSlotSummary").innerHTML = `
          <strong>${btn.dataset.lecturer}</strong> (${btn.dataset.course})<br />
          ${btn.dataset.schedule}<br />
          Type: <strong>${btn.dataset.type}</strong> (${btn.dataset.location})
        `;
        bookingModal.classList.remove("hidden");
      });
    });
  }

  // 6. Student: Open Cancel Modal
  const cancelModal = document.getElementById("cancelModal");
  if (cancelModal) {
    document.querySelectorAll("[data-open-cancel]").forEach((btn) => {
      btn.addEventListener("click", () => {
        document.getElementById("cancelRequestId").value = btn.dataset.requestId;
        document.getElementById("cancelModalMessage").textContent =
          `Are you sure you want to cancel your consultation on ${btn.dataset.summary}?`;
        cancelModal.classList.remove("hidden");
      });
    });
  }

  // 7. Lecturer: Toggle Online Meeting Link vs Face-to-Face Location (Mod 3)
  const typeSelect = document.querySelector("[data-toggle-type]");
  const linkGroup = document.getElementById("meetingLinkGroup");
  const linkInput = document.getElementById("meeting_link");
  const locationInput = document.getElementById("location");
  if (typeSelect && linkGroup) {
    const syncFields = () => {
      const isOnline = typeSelect.value === "Online";
      linkGroup.classList.toggle("hidden", !isOnline);
      if (linkInput) linkInput.required = isOnline;
      if (locationInput) {
        locationInput.placeholder = isOnline ? "e.g. Google Meet / Teams" : "e.g. SOC Building, Room 4.02";
      }
    };
    typeSelect.addEventListener("change", syncFields);
    syncFields();
  }

  // 8. Lecturer: Open Reject Modal
  const rejectModal = document.getElementById("rejectModal");
  if (rejectModal) {
    document.querySelectorAll("[data-open-reject]").forEach((btn) => {
      btn.addEventListener("click", () => {
        document.getElementById("rejectRequestId").value = btn.dataset.requestId;
        document.getElementById("rejectModalStudent").textContent =
          `Provide a reason for rejecting ${btn.dataset.student}'s consultation request.`;
        rejectModal.classList.remove("hidden");
      });
    });
  }

  // 9. Close Any Modal
  document.querySelectorAll("[data-close-modal]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const target = document.getElementById(btn.dataset.closeModal);
      if (target) target.classList.add("hidden");
    });
  });
});
