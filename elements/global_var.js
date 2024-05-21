// Get user cookie
let cookie = document.cookie.indexOf('wp-settings-time') !== -1;

// Regex
const emailPattern = new RegExp("^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,}$");
const indexPattern = new RegExp("^[0-9]{1,}$");
const freeTextPattern = new RegExp("^[a-zA-Z0-9 .?!öäüõ]+$");

// Defaults
let defaultForm = 0;
let currentRealEstate = null;
let currentType = null;
let userConfirmed = false;
