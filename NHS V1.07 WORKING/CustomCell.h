
#import <UIKit/UIKit.h>


@interface CustomCell : UITableViewCell {

	
	IBOutlet UILabel * stateLabel ;
	IBOutlet UILabel * capitalLabel ;
	
	IBOutlet UIButton * detailButton ;
	IBOutlet UIButton * detailButtonRound ;	

 	
}



@property (nonatomic, retain) IBOutlet UILabel * stateLabel ;
@property (nonatomic, retain) IBOutlet UILabel * capitalLabel ;
@property (nonatomic, retain) IBOutlet UIButton * detailButton ;
@property (nonatomic, retain) IBOutlet UIButton * detailButtonRound ;




@end
