document.addEventListener('DOMContentLoaded', () => {
    const userForm = document.getElementById('userForm');
    const groupForm = document.getElementById('groupForm');
    const userGroupForm = document.getElementById('userGroupForm');

    if (userForm) userForm.addEventListener('submit', submitForm);
    if (groupForm) groupForm.addEventListener('submit', submitForm);
    if (userGroupForm) userGroupForm.addEventListener('submit', submitUserGroupForm);

    document.querySelectorAll('.deleteForm').forEach(form => {
        form.addEventListener('submit', submitDeleteForm);
    });
});


async function submitForm(e)
{
    e.preventDefault();

    const form = e.currentTarget;
    const formData = new FormData(form);
    const alertDiv = document.getElementById('alert');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Disabled submit button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Wysyłanie...';

    // Select action: store or update
    const action = form.dataset.action;
    const actionStore = action.startsWith('store-') ? true : false;
    const url = `?action=${action}`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success === true) {
            alertDiv.innerHTML = actionStore ? 'Added successfully!' : 'Updated successfully!';
            alertDiv.style.color = 'green';
            form.reset();
        } else if (result.success === false) {
            alertDiv.innerHTML = Array.isArray(result.errors) ? result.errors.join('<br>') : 'Unknown error';
            alertDiv.style.color = 'red';
        } else {
            alertDiv.innerHTML = 'Unknown error';
            alertDiv.style.color = 'red';
        }

        alertDiv.style.display = 'block';
        setTimeout(() => alertDiv.style.display = 'none', 5000);

    } catch (error) {
        console.error(error);
        alertDiv.innerHTML = `Errors: ${error.message}`;
        alertDiv.style.display = 'block';
        alertDiv.style.color = 'red';
    } finally {
        // Enabled submit button
        submitBtn.disabled = false;
        submitBtn.textContent = actionStore ? 'Add' : 'Update';
    }
}


async function submitUserGroupForm(e)
{
    e.preventDefault();

    const form = e.currentTarget;
    const formData = new FormData(form);
    const alertDiv = document.getElementById('alert');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Disabled submit button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Wysyłanie...';

    // Select action: store or update
    const action = form.dataset.action;
    const url = `?action=${action}`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success === true) {
            alertDiv.innerHTML = 'Added user to group successfully!';
            alertDiv.style.color = 'green';
            form.reset();
            if (result.response) {
                setTimeout(() => {
                    window.location.href = `?action=${result.response}`;
                }, 1500);
            }
        } else if (result.success === false) {
            alertDiv.innerHTML = Array.isArray(result.errors) ? result.errors.join('<br>') : 'Unknown error!';
            alertDiv.style.color = 'red';
        } else {
            alertDiv.innerHTML = 'Unknown error!';
            alertDiv.style.color = 'red';
        }

        alertDiv.style.display = 'block';
        setTimeout(() => alertDiv.style.display = 'none', 5000);

    } catch (error) {
        console.error(error);
        alertDiv.innerHTML = `Errors: ${error.message}!`;
        alertDiv.style.display = 'block';
        alertDiv.style.color = 'red';
    } finally {
        // Enabled submit button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save';
    }
}


async function submitDeleteForm(e) 
{
    e.preventDefault();

    const form = e.currentTarget;
    const formData = new FormData(form);
    const alertDiv = document.getElementById('alert');
    const action = form.dataset.action;
    const url = `?action=${action}`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success === true) {
            alertDiv.innerHTML = 'Deleted successfully!';
            alertDiv.style.color = 'green';
            form.reset();
            if (result.response) {
                console.log(result.response);
                setTimeout(() => {
                    window.location.href = `?action=${result.response}`;
                }, 1500);
            }
        } else if (result.success === false) {
            alertDiv.innerHTML = Array.isArray(result.errors) ? result.errors.join('<br>') : 'Unknown error!';
            alertDiv.style.color = 'red';
        } else {
            alertDiv.innerHTML = 'Unknown error!';
            alertDiv.style.color = 'red';
        }

        alertDiv.style.display = 'block';
        setTimeout(() => alertDiv.style.display = 'none', 5000);

    } catch (error) {
        console.error(error);
        alertDiv.innerHTML = `Errors: ${error.message}!`;
        alertDiv.style.display = 'block';
        alertDiv.style.color = 'red';
    }
}