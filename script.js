const { faker } = require('@faker-js/faker');
const fs = require('fs');
const path = require('path');

function generateUniqueEmail(usedEmails) {
    let email;
    do {
        email = faker.internet.email();
    } while (usedEmails.has(email));
    usedEmails.add(email);
    return email;
}

function generateUsers(count) {
    const usedEmails = new Set();
    let sql = "INSERT INTO users (first_name, last_name, email, phone, age, gender, address) VALUES\n";

    for (let i = 0; i < count; i++) {
        const firstName = faker.person.firstName();
        const lastName = faker.person.lastName();
        const email = generateUniqueEmail(usedEmails);
        const phone = faker.phone.number('###-###-####');
        const age = faker.number.int({ min: 18, max: 80 });
        const gender = faker.person.sex();
        const address = faker.location.streetAddress();

        sql += `('${firstName}', '${lastName}', '${email}', '${phone}', ${age}, '${gender}', '${address.replace(/'/g, "''")}')`;

        if (i < count - 1) {
            sql += ",\n";
        } else {
            sql += ";";
        }
    }

    return sql;
}

// Generate SQL insert statement for 3000 users
const insertStatement = generateUsers(5000);

// Define the file path (project root)
const filePath = path.join(__dirname, 'insert_users.sql');

// Write the SQL to a file
fs.writeFileSync(filePath, insertStatement, 'utf8');

console.log(`SQL file has been saved to ${filePath}`);
