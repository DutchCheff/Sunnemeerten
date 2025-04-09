function updatePermission(postId, value) {
    fetch("database/update-permission.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            post_id: postId,
            is_allowed: value,
        }),
    })
        .then(async (res) => {
            const text = await res.text();
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    console.log("Success!", data);
                } else {
                    console.error("Server returned an error:", data.error || data);
                }
            } catch (e) {
                console.error("JSON parse error:", e, text);
            }
        })
        .catch((err) => {
            console.error("Fetch failed:", err);
        });
}
