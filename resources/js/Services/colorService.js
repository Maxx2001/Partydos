const tailwindColors = [
    "bg-red-300", "bg-red-400", "bg-red-500", "bg-red-600", "bg-red-700",
    "bg-blue-300", "bg-blue-400", "bg-blue-500", "bg-blue-600", "bg-blue-700",
    "bg-green-300", "bg-green-400", "bg-green-500", "bg-green-600", "bg-green-700",
    "bg-yellow-300", "bg-yellow-400", "bg-yellow-500", "bg-yellow-600", "bg-yellow-700",
    "bg-purple-300", "bg-purple-400", "bg-purple-500", "bg-purple-600", "bg-purple-700",
    "bg-pink-300", "bg-pink-400", "bg-pink-500", "bg-pink-600", "bg-pink-700",
    "bg-indigo-300", "bg-indigo-400", "bg-indigo-500", "bg-indigo-600", "bg-indigo-700",
    "bg-teal-300", "bg-teal-400", "bg-teal-500", "bg-teal-600", "bg-teal-700",
    "bg-orange-300", "bg-orange-400", "bg-orange-500", "bg-orange-600", "bg-orange-700",
    "bg-gray-300", "bg-gray-400", "bg-gray-500", "bg-gray-600", "bg-gray-700"
];

const getRandomBgColorFromString = (str) => {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    const index = Math.abs(hash) % tailwindColors.length;

    return tailwindColors[index];
}

export default {
    getRandomBgColorFromString
};
