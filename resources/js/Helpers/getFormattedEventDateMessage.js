// const formatDate = (dateString) => {
//     const options = {
//         day: 'numeric',
//         month: 'long',
//         year: 'numeric',
//         hour: '2-digit',
//         minute: '2-digit',
//     };
//     return new Intl.DateTimeFormat('en-GB', options).format(new Date(dateString));
// };
export const getFormattedEventDateMessage = (event) => {
    return event.formattedDate + " at "  + event.formattedTime;
}
