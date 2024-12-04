import axios from "axios";
export function formatTimestamp(timestamp, includeTime = true) {
    const [dateString, timeString] = timestamp.split(" ");
    const [year, month, day] = dateString.split("-").map(Number);
    const [hour, minute, second] = timeString.split(":").map(Number);
    const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ];
    const ampm = hour >= 12 ? "PM" : "AM";
    const formattedHour = hour % 12 || 12;
    if (month && day && year) {
        if (includeTime) {
            return `${months[month - 1]} ${day}, ${year} ${formattedHour}:${
                minute < 10 ? "0" + minute : minute
            } ${ampm}`;
        } else {
            return `${months[month - 1]} ${day}, ${year}`;
        }
    } else {
        return null;
    }
}

export function addCommasToANumber(number) {
    if (number != null && number != undefined) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    } else {
        return "";
    }
}

export function sentenceCase(thestring) {
    if (thestring != null) {
        return (
            thestring.charAt(0).toUpperCase() + thestring.slice(1).toLowerCase()
        );
    }
}

export function formatNumberAbbreviated(number) {
    number = Number.parseFloat(number)
    const SI_SYMBOL = ["", "K", "M", "B", "T", "P", "E"];
    const tier = Math.floor(Math.log10(number) / 3);
    if (tier === 0) return number.toFixed(1); // Ensure one decimal place if number < 1000

    const suffix = SI_SYMBOL[tier];
    const scale = Math.pow(10, tier * 3);
    const scaledNumber = number / scale;

    const formattedNumber = scaledNumber.toLocaleString("en", {
        maximumFractionDigits: 2, // Adjusted to 1 for one decimal place
    });

    // No need to trim trailing zeros since maximumFractionDigits is set

    return formattedNumber + suffix;
}
export function formatNumberAbbreviatedFullDescription(number) {
    number = Number.parseFloat(number)
    const SI_SYMBOL = ["", "K", "Million", "Billion"];
    const tier = Math.floor(Math.log10(number) / 3);
    if (tier === 0) return number.toFixed(1); // Ensure one decimal place if number < 1000

    const suffix = SI_SYMBOL[tier];
    const scale = Math.pow(10, tier * 3);
    const scaledNumber = number / scale;

    const formattedNumber = scaledNumber.toLocaleString("en", {
        maximumFractionDigits: 3, // Adjusted to 1 for one decimal place
    });

    return formattedNumber + " " + suffix;
}

export function addCommasAndDecToANumber(number, decimal = 2) {
    if (number != null && number != undefined && !isNaN(number)) {
        const parts = number.toString().split(".");
        const integerPart = parts[0];
        const decimalPart = parts.length > 1 ? parts[1] : "";
        const integerWithCommas = integerPart.replace(
            /\B(?=(\d{3})+(?!\d))/g,
            ","
        );
        let formattedDecimalPart = "";
        if (decimal > 0) {
            formattedDecimalPart = "." + (decimalPart + "00").slice(0, decimal);
        } else if (decimal === 0 && decimalPart.length > 0) {
            formattedDecimalPart = "." + decimalPart;
        } else if (decimal === 0) {
            formattedDecimalPart = ".00";
        }
        // console.log(integerWithCommas, number);
        return integerWithCommas + formattedDecimalPart;
    } else {
        return "0.00";
    }
}

export function sanitizeAmount(number, decimal = 2) {
    try {
        return parseFloat(number.replace(/,/g, "").replace(/ /g, ""));
    } catch (e) {
        return number;
    }
}
export function formatCreatedAtToRequiredTimestamp(dateString) {
    if (dateString.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
        return dateString;
    }
    const parts = dateString.split("T");
    const datePart = parts[0];
    const timePart = parts[1].split(".")[0];
    const formattedDate = `${datePart} ${timePart}`;
    return formattedDate;
}
export function calculateIterestOnProduct(
    amountOffered,
    termLength,
    termLengthType,
    productName,
    rate
) {
    // console.log(
    //     "Calculating Interrest",
    //     "A:" +
    //     amountOffered +
    //     "L " +
    //     termLength +
    //     "lp: " +
    //     termLengthType +
    //     "R: " +
    //     rate
    // );
    let cal_interest = 0;
    if (termLengthType === "HISA") {
        cal_interest = Math.round((amountOffered * rate) / 100);
        return cal_interest;
    } else {
        switch (termLengthType) {
            case "DAYS":
                cal_interest = Math.round(
                    (((amountOffered * rate) / 100) * termLength) / 365
                );
                return cal_interest;
                break;
            case "MONTHS":
                cal_interest = Math.round(
                    (((amountOffered * rate) / 100) * termLength) / 12
                );
                return cal_interest;
                break;
        }
    }
}

// new calculateion

export function calculateIterestOnDateCountConnvention(
    principal,
    interstRate,
    convetionType = "Actual_Actual_ISDA",
    startDate,
    termLength,
    termLengthType
) {
    var cal_interest = 0;
    if (principal && interstRate && termLength && termLengthType && startDate) {
        let end_date = new Date(
            addDaysOrMonthsToDate(
                startDate,
                termLength.toString(),
                termLengthType,
                false
            )
        );
        let start_date = new Date(startDate);
        principal = Number.parseFloat(principal);
        interstRate = Number.parseFloat(interstRate);
        termLength = Number.parseFloat(termLength);
        termLengthType = termLengthType.toLowerCase();
        // console.log(start_date, " start date ", end_date)
        // Actual/Actual (A/A)
        if (convetionType == "Actual_Actual_ISDA") {
            let daycount = DayCountConvention.actualActual(
                start_date,
                end_date
            );
            // console.log(daycount,'a a')

            const year = start_date.getFullYear();

            // Check if the year is a leap year
            const isLeapYear =
                (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
            const daysInYear = isLeapYear ? 366 : 365;

            // Calculate the result
            const result =
                principal * (interstRate / 100) * (daycount / daysInYear);
            cal_interest = result;
        }
        // actual over 360
        else if (convetionType == "Actual_360") {
            let daycount = DayCountConvention.actual360(start_date, end_date);
            // console.log(daycount,'a 360')

            cal_interest = principal * (interstRate / 100) * (daycount / 360);
        }
        // actual over 365
        else if (convetionType == "Actual_365") {
            let daycount = DayCountConvention.actual365(start_date, end_date);
            // console.log(daycount,'a 365')

            cal_interest = principal * (interstRate / 100) * (daycount / 365);
        }
        // 30 over 360
        else if (convetionType == "30_360_Bond_Basis") {
            let daycount = DayCountConvention.thirty360(start_date, end_date);
            // console.log(daycount,'30 360 bb')
            cal_interest = principal * (interstRate / 100) * (daycount / 360);
        }
        // console.log(principal, "p ir ", interstRate, " tl ", termLength, termLengthType, " i e ", cal_interest)
        return cal_interest.toFixed(2);
    } else {
        return false;
    }
}
export function capitalize(thestring) {
    if (thestring != undefined) {
        return thestring
            .toLowerCase()
            .split(" ")
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(" ");
    }
}
export function rateTypeCheck(
    rate_type = "fixed",
    rate_operator = "+",
    variable_rate_value = 0
) {
    if (rate_type == "fixed") {
        return "Fixed";
    } else {
        return (
            rate_type.replace(/_/g, " ") +
            " " +
            rate_operator +
            " " +
            variable_rate_value.toFixed(2) +
            "%"
        );
    }
}
export function rateTypeCheckCounter(
    rate_type = "fixed",
    rate_operator = "+",
    variable_rate_value = 0,
    appliedPrate = 0
) {
    if (rate_type == "fixed") {
        return "Fixed";
    } else {
        return (
            rate_type.replace(/_/g, " ") +
            " " +
            rate_operator +
            " " +
            variable_rate_value +
            "%"
        );
    }
}

export function repoProductName(termLength, termLengthType, productName) {
    if (termLengthType?.toLowerCase() === "days") {
        return termLength + " Day " + productName;
    } else {
        return termLength + " Month " + productName;
    }
}

export function addDaysToDate(dateString, daysToAdd) {
    // Convert the string to a Date object
    const date = new Date(dateString);

    // Add the specified number of days
    date.setDate(date.getDate() + daysToAdd);

    // Format the date as "Month Day, Year"
    const options = { month: "short", day: "2-digit", year: "numeric" };
    const formattedDate = date.toLocaleDateString("en-US", options);

    return formattedDate;
}
export function addDaysOrMonthsToDate(
    dateString,
    valueToAdd,
    identifier = "days",
    formtaed = true
) {
    // Convert the string to a Date object

    valueToAdd = Number.parseFloat(valueToAdd);
    let isDays = identifier.toLowerCase() === "days";
    let datetotest =
        typeof dateString == "string" ? dateString : dateString.toString();
    let date = new Date(datetotest);

    // Check if adding days or months
    let finaldate = null;

    if (isDays) {
        // Add the specified number of days
        date.setDate(date.getDate() + valueToAdd);
    } else {
        // Add the specified number of months
        date.setMonth(date.getMonth() + valueToAdd);
    }
    if (formtaed) {
        const options = { month: "short", day: "2-digit", year: "numeric" };
        const formattedDate = date.toLocaleDateString("en-US", options);
        return formattedDate;
    } else {
        const formattedDate = date.toLocaleDateString("en-US");
        return formattedDate;
    }
}

export function getBasketDetails(basket, isbasket = true) {
    let details = null;
    if (isbasket) {
        details = {
            name: basket.basket_details.trade_basket_type.basket_name,
            basket_id: basket.basket_id,
            rating: basket.basket_details.rating,
            // 'basket_id': basket.basket_id,
        };
    } else {
        details = {
            name: basket.collateral_details.collateral_name,
            cucip_code: basket.CUSIP_code,
            rating: basket.trade_organization_collateral.rating,
            // 'basket_id': basket.basket_id,
        };
    }
    return details;
}
export function calculateSettlementLabel(settlemnt) {
    let details = null;
    details =
        settlemnt.trade_date_label +
        " + " +
        settlemnt.duration +
        " " +
        settlemnt.period_label;

    return details;
}

export function dayDiffference(startDate, endDate) {
    let dayfiff = DayCountConvention.actualActual(
        new Date(startDate),
        new Date(endDate)
    );
    let settlemnt = "T + " + dayfiff + " Days";
    return settlemnt;
}
export class DayCountConvention {
    static actualActual(startDate, endDate) {
        const diffTime = Math.abs(endDate - startDate);
        // console.log(diffTime, "diff time")
        return Math.floor(diffTime / (1000 * 60 * 60 * 24)); // Convert ms to days
    }

    static thirty360(startDate, endDate) {
        const d1 = startDate.getDate() === 31 ? 30 : startDate.getDate();
        const d2 = endDate.getDate() === 31 ? 30 : endDate.getDate();

        return (
            (endDate.getFullYear() - startDate.getFullYear()) * 360 +
            (endDate.getMonth() - startDate.getMonth()) * 30 +
            (d2 - d1)
        );
    }

    static actual360(startDate, endDate) {
        const diffTime = Math.abs(endDate - startDate);
        return Math.floor(diffTime / (1000 * 60 * 60 * 24)); // Just return days
    }

    static actual365(startDate, endDate) {
        const diffTime = Math.abs(endDate - startDate);
        return Math.floor(diffTime / (1000 * 60 * 60 * 24)); // Just return days
    }
}
export function validateEmail(value) {
    let error = false;
    const emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
    error = emailRegex.test(value);
    return error;
}

// generate random values

export function generateRandomValue() {
    var characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var length = 12;
    var randomValue = "";
    for (var i = 0; i < length; i++) {
        var randomIndex = Math.floor(Math.random() * characters.length);
        randomValue += characters.charAt(randomIndex);
    }
    return randomValue;
}
