#import <UIKit/UIKit.h>
#import "TTTAttributedLabel.h"

@interface SavedSearchTVC : UITableViewCell {

	IBOutlet UILabel* redDotCountLbl;
	IBOutlet UILabel* titleLbl;
	IBOutlet TTTAttributedLabel* locationSalaryLbl;
	IBOutlet UILabel* blueCountLbl;
	IBOutlet UILabel* frequencyLbl;
	
}

@property(nonatomic,retain) IBOutlet UILabel* redDotCountLbl;
@property(nonatomic,retain) IBOutlet UILabel* titleLbl;
@property(nonatomic,retain) IBOutlet TTTAttributedLabel* locationSalaryLbl;
@property(nonatomic,retain) IBOutlet UILabel* blueCountLbl;
@property(nonatomic,retain) IBOutlet UILabel* frequencyLbl;

@end
