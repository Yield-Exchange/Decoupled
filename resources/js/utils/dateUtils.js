export function formatTimestamp(timestamp) {
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
    return `${months[month - 1]} ${day}, ${year} ${formattedHour}:${
        minute < 10 ? "0" + minute : minute
    } ${ampm}`;
}
