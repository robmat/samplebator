#import <UIKit/UIKit.h>

@interface SearchResultsTC : UITableViewCell {
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* salaryLbl;
	IBOutlet UILabel* descLbl;
}

@property(nonatomic, retain) IBOutlet UILabel* titleLbl;
@property(nonatomic, retain) IBOutlet UILabel* salaryLbl;
@property(nonatomic, retain) IBOutlet UILabel* descLbl;

@end
