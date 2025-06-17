// Declare String Variables
// string variables can hold text data.
var firstName = "John";
var lastName = "Doe";

// Escaping Literal Quotes in Strings
var message = "He said, \"Hello!\"";
console.log(message);
// Quoting Strings with Single Quotes
var singleQuoteMessage = 'He said, "Hello!"';
console.log(singleQuoteMessage);

// Escape Sequences in Strings
var escapeSequenceMessage = "This is the first line.\nThis is the second line.";
console.log(escapeSequenceMessage);

// Concatenating Strings with Plus Operator
var fullName = firstName + " " + lastName;
console.log(fullName);
 
// Concatenating Strings with Plus Equals Operator
var greeting = "Hello, ";
greeting += fullName;
console.log(greeting);

// Strings Constructing Variables with
var ourName = "FreeCodeCamp";
var ourString = "Hello, our name is " + ourName + ".";
console.log(ourString);

// Variables Appending to Strings
var anAdjective = "awesome!";
var ourString = "FreeCodeCamp is ";
ourString += anAdjective;
console.log(ourString);

// Find Length of String
var exampleString = "Hello, World!";
var stringLength = exampleString.length;
console.log("The length of the string is: " + stringLength);

// Find to Notation Bracket String in Character First
var firstCharacter = exampleString[0];
console.log("The first character is: " + firstCharacter);

