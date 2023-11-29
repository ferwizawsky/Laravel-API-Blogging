export const dateFormatter = (e) => {
    return new Date(e)
        .toLocaleString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",

            hour: "2-digit",
            minute: "2-digit",
        })
        .replace("pukul", " - ")
        .replace(".", ":");
};
