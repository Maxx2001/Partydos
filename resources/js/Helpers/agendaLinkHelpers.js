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
        return `intent://details?action=TEMPLATE&text=${encodeURIComponent(
            title
        )}&dates=${isoStartDateTime}/${checkedIsoEndDateTime}&details=${encodeURIComponent(
            description
        )}&location=${encodeURIComponent(location)}#Intent;scheme=https;package=com.google.android.calendar;S.browser_fallback_url=https%3A%2F%2Fcalendar.google.com%2Fcalendar%2Fr%2Feventedit;end;`;
    }

    return `https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
        title
    )}&dates=${isoStartDateTime}/${checkedIsoEndDateTime}&details=${encodeURIComponent(description)}&location=${encodeURIComponent(location)}`;
}


export default googleCalendarLink;
