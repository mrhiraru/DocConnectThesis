document.getElementById('phoneNo').addEventListener('input', function () {
  const prefix = "+63 ";
  if (!this.value.startsWith(prefix)) {
    this.value = prefix;
  }

  const rawDigits = this.value.slice(prefix.length).replace(/\D/g, '');
  let formatted = "";

  if (rawDigits.length > 0) {
    formatted += rawDigits.slice(0, 3);
  }
  if (rawDigits.length > 3) {
    formatted += " " + rawDigits.slice(3, 6);
  }
  if (rawDigits.length > 6) {
    formatted += " " + rawDigits.slice(6, 10);
  }

  this.value = prefix + formatted;
});

// ------OTHERR INFO----
// Show/hide surgery details
document.getElementById('surgeryYes').addEventListener('change', () => document.getElementById('surgeryDetails').classList.remove('d-none'));
document.getElementById('surgeryNo').addEventListener('change', () => document.getElementById('surgeryDetails').classList.add('d-none'));

// Show/hide allergy details
document.getElementById('allergyYes').addEventListener('change', () => document.getElementById('allergyDetails').classList.remove('d-none'));
document.getElementById('allergyNo').addEventListener('change', () => document.getElementById('allergyDetails').classList.add('d-none'));

// Show/hide medication details
document.getElementById('medicationsYes').addEventListener('change', () => document.getElementById('medicationList').classList.remove('d-none'));
document.getElementById('medicationsNo').addEventListener('change', () => document.getElementById('medicationList').classList.add('d-none'));

// Add more medication fields
document.getElementById('addMedication').addEventListener('click', () => {
  const container = document.createElement('div');
  container.classList.add('medication-entry', 'mb-2');
  container.innerHTML = `<input type="text" class="form-control mb-2" name="medication_name[]" placeholder="Medication Name">
                         <input type="text" class="form-control mb-2" name="medication_dosage[]" placeholder="Dosage">
                         <input type="text" class="form-control mb-2" name="medication_frequency[]" placeholder="Frequency">
                         <input type="text" class="form-control mb-2" name="medication_purpose[]" placeholder="Purpose">
                         `;
  document.getElementById('medicationList').appendChild(container);
});

// Show/hide vaccination details
document.getElementById('vaccinationsYes').addEventListener('change', () => document.getElementById('vaccinationList').classList.remove('d-none'));
document.getElementById('vaccinationsNo').addEventListener('change', () => document.getElementById('vaccinationList').classList.add('d-none'));

// Add more vaccination fields
document.getElementById('addVaccination').addEventListener('click', () => {
  const container = document.createElement('div');
  container.classList.add('vaccination-entry', 'mb-2');
  container.innerHTML = `<input type="text" class="form-control mb-2" name="vaccine_name[]" placeholder="Vaccine Name">
                         <input type="date" class="form-control mb-2" name="vaccine_date[]">
                         <select class="form-control mb-2" name="booster_required[]">
                         <option value="Yes">Booster Required</option>
                         <option value="No">No Booster Required</option>
                         </select>
                         `;
  document.getElementById('vaccinationList').appendChild(container);
});