export async function getGenres_DB() {
  const result = await fetch("../../../backend/genres/get_genres.php");

  return result.json();
}
