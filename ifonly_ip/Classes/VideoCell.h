#import <UIKit/UIKit.h>

@interface VideoCell : UITableViewCell {
	IBOutlet UILabel* dateLbl;
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* durationLbl;
	
}

@property (nonatomic, retain) IBOutlet UILabel* dateLbl;
@property (nonatomic, retain) IBOutlet UILabel* titleLbl;
@property (nonatomic, retain) IBOutlet UILabel* durationLbl;

@end
