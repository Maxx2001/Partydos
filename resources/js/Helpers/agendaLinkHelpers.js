const googleCalendarLink =  (
        isoStartDateTime,
        isoEndDateTime,
        title,
        description,
        location) =>  {
        const checkedIsoEndDateTime = isoEndDateTime ? isoEndDateTime : isoStartDateTime;
        return `https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
            title
        )}&dates=${isoStartDateTime}/${checkedIsoEndDateTime}&details=${encodeURIComponent(description)}&location=${encodeURIComponent(location)}`;
    }


export default googleCalendarLink;
