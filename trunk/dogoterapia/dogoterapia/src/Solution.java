import java.util.Arrays;

public class Solution {
  public int solution(int[] A) {
    Arrays.sort(A);
    for (int i = 0; i < A.length - 2; i++) {
      int p = i;
      int q = i + 1;
      int r = i + 2;
      if (A[p] + A[q] > A[r] && A[q] + A[r] > A[p] && A[r] + A[p] > A[q]) {
        return 1;
      }
    }
    return 0;
  }

  public static void main(String[] args) {
    System.out.println(new Solution().solution(new int[]{10, 2, 5, 1, 8, 20}));
    System.out.println(new Solution().solution(new int[]{10, 50, 5, 1}));
    System.out.println(new Solution().solution(new int[]{10, 2, 5, 1, 8, 20}));
    System.out.println(new Solution().solution(new int[]{10, 50, 5, 1}));
  }
}
