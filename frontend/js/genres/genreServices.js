export async function fetch_genres_DB() {
  const result = await fetch("../backend/genres/load_genres.php");

  return result.json();
}
