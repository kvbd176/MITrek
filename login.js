const container = document.querySelector('.container');
const registerbtn = document.querySelector('.register-btn');
const loginbtn = document.querySelector('.login-btn');

// Switch to Register panel
registerbtn.addEventListener('click', () => {
    container.classList.add('active');
});

// Switch to Login panel
loginbtn.addEventListener('click', () => {
    container.classList.remove('active');
});

// REGISTRATION
document.getElementById('register-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    
    const username = document.getElementById('REG_NAME').value.trim();
    const email = document.getElementById('REG_EMAIL').value.trim();
    const store = document.getElementById('REG_STORE').value.trim();
    const password = document.getElementById('REG_PASSWORD').value;

    const formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('store', store);
    formData.append('password', password);

    try {
        const res = await fetch('register.php', {
            method: 'POST',
            body: formData
        });

        const text = await res.text();
        if (text === 'USERNAME_EXISTS') {
            alert('Username already taken.');
        } else if (text === 'REGISTERED') {
            alert('Registered successfully! Please login.');
            container.classList.remove('active');
        } else {
            alert('Registration failed. Server says: ' + text);
        }
    } catch (err) {
        alert('Registration error: ' + err.message);
    }
});

// LOGIN
document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const username = document.getElementById('LOGIN_NAME').value.trim();
    const password = document.getElementById('LOGIN_PASSWORD').value;

    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    try {
        const res = await fetch('login.php', {
            method: 'POST',
            body: formData
        });

        const text = await res.text();
        if (text === 'SUCCESS') {
            alert('Login successful!');
            window.location.href = 'mpage.php';
        } else if (text === 'WRONG_PASSWORD') {
            alert('Wrong password.');
        } else if (text === 'NOT_FOUND') {
            alert('Username not found.');
        } else {
            alert('Login failed. Server says: ' + text);
        }
    } catch (err) {
        alert('Login error: ' + err.message);
    }
});
