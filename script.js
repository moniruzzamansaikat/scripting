const { faker } = require('@faker-js/faker');
const fs = require('fs');
const path = require('path');

// Function to escape single quotes in SQL values
function escapeString(value) {
    return value.replace(/'/g, "''");
}

// Function to generate a unique email that hasn't been used
function generateUniqueEmail(usedEmails) {
    let email;
    do {
        email = faker.internet.email();
    } while (usedEmails.has(email));
    usedEmails.add(email);
    return email;
}

// Function to generate users
function generateUsers(count) {
    const usedEmails = new Set();
    let sql = "INSERT INTO users (first_name, last_name, email, phone, age, gender, address) VALUES\n";

    for (let i = 0; i < count; i++) {
        const firstName = escapeString(faker.name.firstName());
        const lastName = escapeString(faker.name.lastName());
        const email = escapeString(generateUniqueEmail(usedEmails));
        const phone = faker.phone.number('(###) ###-####'); // Valid phone format
        const age = faker.number.int({ min: 18, max: 80 }); // Realistic age range
        const gender = escapeString(faker.person.sex()); // More realistic gender
        const address = escapeString(faker.location.streetAddress()); // Valid address format

        // Construct the SQL insert statement
        sql += `('${firstName}', '${lastName}', '${email}', '${phone}', ${age}, '${gender}', '${address}')`;

        // Add commas between rows except for the last one
        if (i < count - 1) {
            sql += ",\n";
        } else {
            sql += ";";
        }
    }

    return sql;
}

// Generate SQL insert statement for 1000 users
const insertStatement = generateUsers(50000);

// Define the file path (project root)
const filePath = path.join(__dirname, 'insert_users.sql');

// Write the SQL to a file
fs.writeFileSync(filePath, insertStatement, 'utf8');

console.log(`SQL file has been saved to ${filePath}`);
