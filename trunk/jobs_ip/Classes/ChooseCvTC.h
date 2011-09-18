#import <UIKit/UIKit.h>

@interface ChooseCvTC : UITableViewCell {

	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* dateLbl;
	IBOutlet UILabel* descLbl;
}

@property(nonatomic,retain) IBOutlet UILabel* titleLbl;
@property(nonatomic,retain) IBOutlet UILabel* dateLbl;
@property(nonatomic,retain) IBOutlet UILabel* descLbl;

@end
