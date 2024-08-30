function isMobile() {
    return /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
}

const googleCalendarLink =  (
    isoStartDateTime,
    isoEndDateTime,
    title,
    description,
    location) =>  {
    const checkedIsoEndDateTime = isoEndDateTime ? isoEndDateTime : isoStartDateTime;

    if(isMobile()) {
        return `https://calendar.google.com/calendar/u/0/r/eventedit?text=${encodeURIComponent(
           title
        )}&dates=${isoStartDateTime}/${isoEndDateTime}&details=${encodeURIComponent(
            description
        )}&location=${encodeURIComponent(location)}`;
    }

    return `https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
        title
    )}&dates=${isoStartDateTime}/${checkedIsoEndDateTime}&details=${encodeURIComponent(description)}&location=${encodeURIComponent(location)}`;
}


export default googleCalendarLink;
